<?php
namespace App\Http\Reports;

use App\Variables\Globals;
use App\Http\Helpers\Format;

class GeneratePdf {
  function __construct($data)  {
    $this->view = "";

    $this->reportTypes = [
      'generic' => 'Generic',
      'ecg' => 'ECG',
      'cbc' => 'CBC',
      'parasitology' => 'Parasitology',
      'serology' => 'Serology',
      'urinalysis' => 'Urinalysis',
      'chemistry' => 'Chemistry',
    ];

    $this->data = $data;

    $this->patient = $data->patient;

    $this->patient['id_string'] = $this->patient->idc_type ? "{$this->patient->idc_type->name} #{$this->patient->idc_number}" : '';
    $this->patient['birth_date'] = Format::DateDisplay($this->patient->birth_date);
    $this->patient['gender'] = ucwords(strtolower($data->patient->gender));

    $this->procedures = $this->sortProcedures($data->procedures);

    // Globals::test($this->procedures->toArray());

    $this->css();
    $this->createTables();
  }

  private function css() {
    ob_start();
    include base_path("public/Reports/css/main.css");
    $this->view .= "<style>" . ob_get_clean() . "</style>";
  }

  private function getCategoryType($procedures) {
    $keys = array_keys($procedures);
    return $procedures[$keys[0]]->procedure->procedure_type_category->procedure_type;
  }

  private function sortProcedures($procedures) {   
    return $procedures->groupBy(function($procedure) {
      return $procedure->procedure->procedure_type_category->procedure_type;
    })
    ->reduce(function($array, $procedure) {
      $array->push((object)[
        'type' => $procedure[0]->procedure->procedure_type_category->procedure_type,
        'procedures' => $procedure->groupBy(function($procedure) {
          return $procedure->procedure->procedure_type_category->id;
        })->reduce(function($array, $procedure) {
          $array->push((object)[
            'id' => $procedure[0]->procedure->procedure_type_category->id,
            'name' => $procedure[0]->procedure->procedure_type_category->name,
            'report_type' => $procedure[0]->procedure->procedure_type_category->report_type,
            'procedures' => $procedure->sortBy(function($item) {
              return $item->procedure->sort;
            })
          ]);

          return $array;
        }, collect([]))
      ]);


      return $array;
    }, collect([]));
  }

  private function getTypeLocationProceed($type) {
    return Globals::$typesProceedTo[$type];
  }

  private function getReportInstance($type) {
    return "App\\Http\\Reports\\" . $this->reportTypes[$type];
  }

  private function generateTableOfType($description, $type, $procedures) {
    $instance = $this->getReportInstance($type);
    $report = new $instance($description, $this->patient, $procedures);

    $this->view .= $report->html();
  }

  private function createTables() {
    $this->procedures->each(function($type) {
      $type->procedures->each(function($category) use ($type) {
        $description = (object)[
          'type' => $type->type,
          'category' => $category->name,
          'description' => $this->getTypeLocationProceed($type->type),
          'reference_number' => $this->data->reference_number,
          'created_at' => Format::DateDisplay($this->data->created_at),
          'physician' => $this->data->attending_physician
        ];
        $this->generateTableOfType($description, $category->report_type, $category->procedures);
      });
    });
  }
  
  function show() {
    return $this->view;
  }
}