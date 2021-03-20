<?php
namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\LookupTable;
use App\Variables\Procedures;
use App\Procedure;
use App\Patient;
use App\PatientRecord;
use App\ProcedureTypeCategory;
use Carbon\Carbon;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Variables\Procedures as Variables;

function log($var) {
  echo "<pre>";
  print_r($var);
}

class TestController extends Controller {

    public function index() {
      echo '<pre>';
      
      // $types = array_slice(Procedures::$laboratory, 0, 3);
      // $types = ProcedureTypeCategories::fetchAll()->toArray();

      // $laboratory = ProcedureTypeCategories::getType($types, Procedures::$types[2]);
      
      // print_r($laboratory);

      // $array = Procedure::with(['procedure_type_categories'])->get(['id', 'name', 'details', 'amount', 'disabled', 'procedure_type_categories_id'])->toArray();
      // $array = PatientRecord::with(['procedures', 'patient' ,'cashier', 'cancelled'])->where('id', 1)
      // $array = PatientRecord::with([
      //           'procedures',
      //           'procedures.procedure',
      //           'procedures.procedure.procedure_type_categories',
      //           'patient',
      //           'cashier',
      //           'cancelled'
      //         ])->where('id', 1)
      //         ->get();
        
      // $array = Procedure::with([
      //   'procedure_type_categories'
      // ])
      // ->get();

      // $array = Patient::fetchAll();

      print_r(
        ProcedureTypeCategory::selection()->with(['procedures'])->where('disabled', 0)->orderBy('index', 'ASC')->get()->groupBy('procedure_type')->toArray()
      );


      echo '</pre>';
      return;
    }

    public function maggi() {

      $this->spreadsheet = IOFactory::load(base_path() . '\\public\\maggi.xlsx');

      $englishPrinting = file_get_contents(base_path() . '\\public\\english_printing.json');
      $englishEmail = file_get_contents(base_path() . '\\public\\english_email.json');
      
      $arabicPrinting = file_get_contents(base_path() . '\\public\\arabic_printing.json');
      $arabicEmail = file_get_contents(base_path() . '\\public\\arabic_email.json');

      $englishPrintingArray = json_decode($englishPrinting, true);
      $englishEmailArray = json_decode($englishEmail, true);

      $arabicPrintingArray = json_decode($arabicPrinting, true);
      $arabicEmailArray = json_decode($arabicEmail, true);

      $this->plotToTable(2, 2, $englishPrintingArray);
      $this->plotToTable(2, 18, $englishEmailArray);

      $this->plotToTable(2, 35, $arabicPrintingArray);
      $this->plotToTable(2, 51, $arabicEmailArray);

      $this->download();
    }
    
    private function plotToTable($letterIndex, $start, $array) {
      $startIndex = $letterIndex;
      $activeSheet = $this->activeSheet();
      foreach ($array as $key => $value) {
        $letter = Variables::letter($letterIndex);
        $activeSheet->setCellValue("{$letter}{$start}", date('M j', strtotime($key)));
        $this->plotToColumn($letterIndex, $start + 1, $key, $value);

        $letterIndex++;
      }


      $this->createTotal($startIndex, $letterIndex, $start + 1);
    }

    private function plotToColumn($letterIndex, $start, $key, $array) {
      $activeSheet = $this->activeSheet();
      $letter = Variables::letter($letterIndex);
      foreach ($array as $key => $value) {
        $index = $start + $key;
        $activeSheet->setCellValue("{$letter}{$index}", $value);
      }
    }

    private function createTotal($startIndex, $endIndex, $start) {
      $activeSheet = $this->activeSheet();
      $letter = Variables::letter($endIndex);
      $startLetter = Variables::letter($startIndex);
      $endLetter = Variables::letter($endIndex - 1);

      for ($i = 0; $i <= 12; $i++) {
        $index = $start + $i;
        $activeSheet->setCellValue("{$letter}{$index}", "=SUM({$startLetter}{$index}:{$endLetter}{$index})");
      }

      $index = $start + $i;
      $activeSheet->setCellValue("{$endLetter}{$index}", "Total");

      $end = $index - 1;
      $activeSheet->setCellValue("{$letter}{$index}", "=SUM({$letter}{$start}:{$letter}{$end})");
    }

    private function activeSheet() {
      return $this->spreadsheet->getActiveSheet();
    }

    
  function download() {
    $writer = IOFactory::createWriter($this->spreadsheet, "Xlsx");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=maggi_data.xlsx");
    $writer->save("php://output");
  }    
}
