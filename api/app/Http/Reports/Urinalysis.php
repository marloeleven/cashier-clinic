<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class Urinalysis extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('urinalysis');
  }

  function html() {
    return $this->view;
  }
}