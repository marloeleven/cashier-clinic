<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class ECG extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('ecg');
  }

  function html() {
    return $this->view;
  }
}