<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class Chemistry extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('chemistry');
  }

  function html() {
    return $this->view;
  }
}