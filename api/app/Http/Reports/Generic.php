<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class Generic extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('generic');
  }

  function html() {
    return $this->view;
  }
}