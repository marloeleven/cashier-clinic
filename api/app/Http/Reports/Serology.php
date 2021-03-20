<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class Serology extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('serology');
  }

  function html() {
    return $this->view;
  }
}