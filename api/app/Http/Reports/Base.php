<?php
namespace App\Http\Reports;
use App\Variables\Globals;
use App\Http\Helpers\Format;
use Carbon\Carbon;

class Base {
  function __construct($description, $patient, $procedures)  {
    $this->view = "";

    $this->dynamicElements = "";

    $patient->age = Carbon::createFromFormat("F j, Y", $patient->birth_date)->age;
    
    $this->description = $description;
    $this->patient = $patient;
    $this->procedures = $procedures;
  }

  function getContents($file) {

    extract([
      'patient' => $this->patient,
      'header' => (object)[
        'main' => Globals::$typesProperNames[$this->description->type],
        'sub' => $this->description->category,
        'description' => $this->description->description,
        'reference_number' => $this->description->reference_number,
        'created_at' => $this->description->created_at,
        'physician' => $this->description->physician
      ],
      'procedures' => $this->procedures,
      'format' => 'App\Http\Helpers\Format'
    ]);

    ob_start();
    include base_path("public/Reports/{$file}.php");
    $this->view .= ob_get_clean();

    return $this;
  }

  
  function show() {
    return $this->view;
  }
}