<?php
namespace App\Http\ExcelReports;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use App\Variables\Procedures as Variables;
use App\Http\Helpers\Compute;

use App\Procedure as ProcedureModel;

define('DEBUG', !true);
define('COLUMN_START', 6);
define('ROW_START', 6);
define('COLUMN_MIN_WIDTH', 20);
define('COLUMN_PROCEDURE_MIN_WIDTH', 20);

function _print($text, $label = null) {
  if (!DEBUG) {
    return;
  }

  echo "
    <style>
      body {
        background: black;
        color: white;
      }
    </style>
  ";
  echo "<pre>";
  echo "<br>";
  if ($label) {
    echo $label . ' ';
  }

  if (is_array($text)) {
    print_r($text);
    return;
  }
  
  echo $text;
}

class Procedure {

  private $sortByIndex;

  private $rowStart = ROW_START;

  function __construct($from, $to, $data, $user) {
    // procedure column id holder
    $this->procedureColumn = collect([]);

    $this->sortByIndex = function($item) {
      return $item->procedure->procedure_type_category->index;
    };

    $this->sortProcedureBySortOrderASC = function($item) {
      return $item->procedure->sort;
    };

    $spreadsheet = new Spreadsheet();
    
    $spreadsheet->getProperties()
    ->setTitle("Cash Sales Report")
    ->setSubject("Cash Sales Report From {$from} to {$to}");
    
    $time = Carbon::now()->format('Y-m-d-h-m-a');

    $filename = "{$user}_$time";
    
    $this->spreadsheet = $spreadsheet;
    $this->filename = $filename;

    $this->header($from, $to);

    $this->columnHeader($data);
	
    $this->plotData($data);

    if (!DEBUG) {
      $this->download();
    }
  }

  private function displayWholeDate($date) {
    return "{$date->format('F')} {$date->day}, {$date->year}";
  }

  private function getDateDisplay($from, $to) {
    if ($from->isSameDay($to)) {
      return $this->displayWholeDate($from);
    }

    if ($from->isSameMonth($to, true)) {
      return "{$from->format('F')} {$from->day}-{$to->day}, {$from->year}";
    }
    
    return "{$this->displayWholeDate($from)} - {$this->displayWholeDate($to)}";
  }
  
  private function setHeader($from, $to) {
    $date = $this->getDateDisplay($from, $to);

    $sheet = $this->activeSheet();
    
    $sheet->setCellValue('A1', "GOOD SHEPHERD LABORATORY AND HEALTH DIAGNOSTICS SERVICES, INC.");
    $sheet->setCellValue('A2', "CASH SALES Report");
    $sheet->setCellValue('A3', "Data From - To");
    $sheet->setCellValue('B3', $date);
    $sheet->setCellValue('A4', "Report Generation");
    $sheet->setCellValue('B4', Carbon::now()->format('F j, Y h:m a'));
  }

  private function header($from, $to) {
    $from = Carbon::parse($from);
    $to = Carbon::parse($to);

    if ($from->gt($to)) {
      $this->setHeader($to, $from);
      return;
    }

    $this->setHeader($from, $to);
  }

  /* COLUMN */

  private function setHeaderBorder($activeSheet, $cell) {
    $headerStyle = [
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
          'argb' => 'FF92D050'
        ]
      ],
      'borders' => [
        'top' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'left' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'right' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'bottom' => [
          'borderStyle' => Border::BORDER_THIN
        ]
      ]
    ];

    $activeSheet->getStyle($cell)->applyFromArray($headerStyle);
  }

  private function setColumnFormat($activeSheet, $rowEnd) {
    $rowStart = ROW_START + 3;
    $colEnd = COLUMN_START + $this->procedureColumn->count() - 1;
    $colStartLetter = Variables::letter(COLUMN_START);
    $colEndLetter = Variables::letter($colEnd);

    $activeSheet->getStyle("D{$rowStart}:D{$rowEnd}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $activeSheet->getStyle("E{$rowStart}:E{$rowEnd}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $activeSheet->getStyle("{$colStartLetter}{$rowStart}:{$colEndLetter}{$rowEnd}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $headerStart = ROW_START;
    $headerEnd = ROW_START + 2;

    for ($row = $headerStart; $row <= $headerEnd; $row++) {
      for ($col = 0; $col <= $colEnd; $col++) {
        $letter = Variables::letter($col);
        $this->setHeaderBorder($activeSheet, "{$letter}{$row}");
      }
    }
  }

  private function computeTotal($procedures) {

    $proceduresArrange = $procedures->reduce(function($accum, $item) {
      $model = new ProcedureModel();

      $model['amount'] = $item->amount;
      $model['patient_record'] = $item->patient_record;
      $model['procedure_type_category'] = $item->procedure->procedure_type_category;

      $accum->push($model);
      return $accum;
    }, collect([]));

    $information = $procedures->first()->patient_record;

    $discount_percentage = $information->discount ? $information->discount->amount : 0;
    $senior_citizen_discount = $information->senior_citizen_discount;

    return Compute::compute($proceduresArrange, $discount_percentage / 100, $senior_citizen_discount);
  }

  private function totalRow($activeSheet, $rowEnd) {
    $rowStart = ROW_START + 3;
    $lastRow = $rowEnd - 1;
    $colEnd = COLUMN_START + $this->procedureColumn->count() - 1;
    for ($col = 3; $col <= $colEnd; $col++) {
      if ($col !== 5) {
        $letter = Variables::letter($col);
        $activeSheet->setCellValue("{$letter}{$rowEnd}", "=SUM({$letter}{$rowStart}:{$letter}{$lastRow})");
      }
    }

    $headerStyle = [
      'borders' => [
        'top' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'left' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'right' => [
          'borderStyle' => Border::BORDER_THIN
        ],
        'bottom' => [
          'borderStyle' => Border::BORDER_THIN
        ]
      ]
    ];

    $startColumnLetter = Variables::letter(2);
    $lastColumnLetter = Variables::letter($colEnd);

    $activeSheet->setCellValue("{$startColumnLetter}{$rowEnd}", "Total");
    $activeSheet->getStyle("{$startColumnLetter}{$rowEnd}:{$lastColumnLetter}{$rowEnd}")->applyFromArray($headerStyle);
  }

  private function setHeaderStyle($activeSheet, $count) {
    $rowStart = ROW_START;
    $rowEnd = ROW_START + 2;

    $totalColumns = $count + COLUMN_START - 1;

    $startLetter = Variables::letter(0);
    $endLetter = Variables::letter($totalColumns);

    $activeSheet->getStyle("{$startLetter}{$rowStart}:{$endLetter}{$rowEnd}")
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);
    
    for($i = 0; $i <= $totalColumns; $i++) {
      if ($i <= COLUMN_START) {
        $activeSheet->getColumnDimension(Variables::letter($i))->setWidth(COLUMN_MIN_WIDTH);
        continue;
      }

      $activeSheet->getColumnDimension(Variables::letter($i))->setWidth(COLUMN_PROCEDURE_MIN_WIDTH);
    }
  }

  private function generateMergedColumn($activeSheet, &$start, $count, $value) {
    $letter = Variables::letter($start);

    if ($count > 1) {
      $start = $start + $count - 1;
      $endLetter = Variables::letter($start);

      $activeSheet->mergeCells("{$letter}{$this->rowStart}:{$endLetter}{$this->rowStart}");
      $activeSheet->setCellValue("{$letter}{$this->rowStart}", $value);
      return "{$letter}{$this->rowStart}";
    }

    $activeSheet->setCellValue("{$letter}{$this->rowStart}", $value);

    return "{$letter}{$this->rowStart}";
  }

  private function generateColumnForSubCategory($activeSheet, &$column, $data) {    
    $uniqueSubCategory = $data->unique(function($item) {
      return $item->procedure->procedure_type_category->id;
    })->values();
    
    $uniqueSubCategory->each(function($subCategory) use ($activeSheet, &$column, $data) {
      $procedures = $data->filter(function($item) use ($subCategory) {
        return $item->procedure->procedure_type_category->id === $subCategory->procedure->procedure_type_category->id;
      });
      
      $uniqueProcedures = $procedures->unique(function($item) {
        return $item->procedure->id;
      });

      $this->generateMergedColumn($activeSheet, $column, $uniqueProcedures->count(), $procedures->first()->procedure->procedure_type_category->name);
      $column++;
    });
  }

  private function generateColumnForProcedureType($activeSheet, &$column, $procedureType, $data) {
    $procedures = $data->sortBy($this->sortByIndex);
    
    $uniqueProcedures = $procedures->unique(function($item) {
      return $item->procedure->id;
    })->sortBy($this->sortProcedureBySortOrderASC)->values();

	
    $uniqueProcedures->each(function($item) {
		
      $this->procedureColumn->push($item->procedure->id);
    });

    $count = $uniqueProcedures->count();

    $this->generateMergedColumn($activeSheet, $column, $count, $procedureType);
  }

  private function createDynamicColumn($activeSheet, $data) {
    $procedureTypes = $data->groupBy(function($item) {
      return $item->procedure->procedure_type_category->procedure_type;
    });
    
    $columnStart = COLUMN_START;

    foreach (Variables::$types AS $procedureType) {
      if ($procedureTypes->has($procedureType)) {
        $filteredData = $procedureTypes->get($procedureType);

        $this->generateColumnForProcedureType($activeSheet, $columnStart, $procedureType, $filteredData);

        $columnStart++;
      }
    }

    $this->rowStart++;

    
    $columnStart = COLUMN_START;

    foreach (Variables::$types AS $procedureType) {
      if ($procedureTypes->has($procedureType)) {
        $filteredData = $procedureTypes->get($procedureType)->sortBy($this->sortByIndex)->values();

        $this->generateColumnForSubCategory($activeSheet, $columnStart, $filteredData);
      }
    }

    $this->rowStart++;
    
    $columnStart = COLUMN_START;

    $uniqueProcedures = $data->unique(function($item) {
      return $item->procedure->id;
    })->values();

    $this->procedureColumn->each(function($id) use ($uniqueProcedures, $activeSheet, &$columnStart) {
      $procedure = $uniqueProcedures->filter(function($item) use ($id) {
        return $item->procedure->id === $id;
      })->first();

      $this->generateMergedColumn($activeSheet, $columnStart, 1, $procedure->procedure->name);
      $columnStart++;
    });

    $this->setHeaderStyle($activeSheet, $uniqueProcedures->count());
  }

  private function columnHeader($data) {
    $sheet = $this->activeSheet();

    $rowStart = ROW_START;
    $rowEnd = ROW_START + 2;
    
    $sheet->mergeCells("A{$rowStart}:A{$rowEnd}");
    $sheet->setCellValue("A{$rowStart}", "OFFICIAL RECEIPT #");
    
    $sheet->mergeCells("B{$rowStart}:B{$rowEnd}");
    $sheet->setCellValue("B{$rowStart}", "Patient's Name");
    
    $sheet->mergeCells("C{$rowStart}:C{$rowEnd}");
    $sheet->setCellValue("C{$rowStart}", "Requesting Doctor");
    
    $sheet->mergeCells("D{$rowStart}:D{$rowEnd}");
    $sheet->setCellValue("D{$rowStart}", "Total Amount");
    
    $sheet->mergeCells("E{$rowStart}:E{$rowEnd}");
    $sheet->setCellValue("E{$rowStart}", "Discounted Amount");
    
    $sheet->mergeCells("F{$rowStart}:F{$rowEnd}");
    $sheet->setCellValue("F{$rowStart}", "Discount %");

    $this->createDynamicColumn($sheet, $data);
  }

  private function plotData($data) {
    $activeSheet = $this->activeSheet();

    $groupByReferenceNumber = $data
    ->sortBy(function($item) {
      return $item->patient_record->created_at;
    })
    ->groupBy(function($item) {
        return $item->patient_record->reference_number;
    });
	    
    $this->rowStart = ROW_START + 3;
    $groupByReferenceNumber->each(function($procedures) use ($activeSheet) {
      $columnStart = COLUMN_START;

      $details = $this->computeTotal($procedures);

      $information = $procedures->first();

      $discount = $information->patient_record->discount ? $information->patient_record->discount->amount : '';
      $startColumn = 0;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $information->patient_record->reference_number);
      $startColumn++;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $information->patient_record->patient->full_name);
      $startColumn++;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $information->patient_record->attending_physician);
      $startColumn++;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $details->total_amount);
      $startColumn++;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $details->discounted_amount ?: '');
      $startColumn++;
      $this->generateMergedColumn($activeSheet, $startColumn, 1, $discount);


	  $mappedProcedures = $procedures->reduce(function($array, $procedure) {
		$array[$procedure->procedure_id] = $procedure->amount;
		 
		return $array;
	  }, collect([]));
	  
	
      $this->procedureColumn->each(function($id) use ($activeSheet, $mappedProcedures, &$columnStart) {	
		$amount = isset($mappedProcedures[$id]) ? $mappedProcedures[$id] : '';	  
		$this->generateMergedColumn($activeSheet, $columnStart, 1, $amount);
        
        $columnStart++;
      });

      $this->rowStart++;
    });		

    $this->setColumnFormat($activeSheet, $this->rowStart);

    $this->totalRow($activeSheet, $this->rowStart);
  }

  private function activeSheet() {
    return $this->spreadsheet->getActiveSheet();
  }

  function download() {
    $writer = IOFactory::createWriter($this->spreadsheet, "Xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename={$this->filename}.xlsx");
    $writer->save("php://output");
  }
}

/*
  set border
  
  set Cell format


  header with green bg


*/