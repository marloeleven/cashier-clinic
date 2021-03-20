<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class CBC extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('cbc');
  }

  function html() {
    return $this->view;
  }
}