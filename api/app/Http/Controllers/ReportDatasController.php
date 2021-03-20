<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ReportData;
use App\User;
use App\PatientRecordProcedure;
use App\Http\ExcelReports\Procedure;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportDatasController extends Controller {
    // $activeSheet->getColumnDimension('D')->setWidth(20);
    // $activeSheet->getStyle('A1:D5')->getFont()->setBold(true);
    // $activeSheet->getStyle("D{$startRow}:F{$index}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    // $activeSheet->getStyle('C6:F100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    /* NEW */

    private function getColumnFields($data) {
        $groupByProcedureType = $data->groupBy(function($item) {
            return $item->procedure->procedure_type_category->procedure_type;
        });

        $uniqueColumnsPerProcedure = $groupByProcedureType->reduce(function($collect, $proceudreTypeData) {
            $proceudreType = $proceudreTypeData[0]->procedure->procedure_type_category->procedure_type;

            $prodecures = $proceudreTypeData->unique(function($item) {
                return $item->procedure->procedure_type_categories_id;
            })->sortBy(function($item) {
                return $item->procedure->procedure_type_category->index;
            })->values();

            $collect->put($proceudreType, $prodecures);
    
            return $collect;
        }, collect([]));
        
        return $uniqueColumnsPerProcedure;
    }

    public function exportProcedures($cashier, $from, $to, $ids) {
        $data = PatientRecordProcedure::with(['patient_record', 'patient_record.patient', 'patient_record.discount', 'procedure', 'procedure.procedure_type_category'])
        ->whereBetween('created_at', [date($from . " 00:00:00"), date($to . " 23:59:59")])
        ->whereIn('procedure_id', explode(',', $ids))
        ->whereDisabled(0)
        ->get();

        if ($cashier !== 'all') {
            $data = $data->filter(function($value) use ($cashier) {
                return $value->patient_record->cashier_id == $cashier;
            });

            $user = strtolower(User::find($cashier)->full_name);
            $user = str_replace('.', '', $user);
            $user = preg_replace('/[\s]+/', '_', $user);
            $cashier = filter_var($user, FILTER_SANITIZE_STRING);
        }
        
        $spreadsheet = new Procedure($from ,$to, $data, $cashier);
    }

    /* BUILD */

    private function row(array $columns, $options = []) {
        $attributes = [];
        if (count($options)) {
            
        }

        array_unshift($columns, "<tr>");
        array_push($columns, '</tr>');

        return implode('', $columns);
    }

    private function column($string) {
        return "<td>{$string}</td>";
    }

    private function buildColumn($data) {
        $tableArray = ['<table>'];

        array_push($tableArray, $this->row([
            $this->column('test'),
            $this->column('test2'),
            $this->column('test3'),
        ]));
        array_push($tableArray, '</table>');

        
        echo implode('', $tableArray);
    }
}
