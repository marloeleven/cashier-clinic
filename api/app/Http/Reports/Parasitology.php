<?php
namespace App\Http\Reports;
use App\Http\Reports\Base;

class Parasitology extends Base {
  function __construct($description, $patient, $procedures)  {
    parent::__construct($description, $patient, $procedures);

    $this->getContents('parasitology');
  }

  function html() {
    return $this->view;
  }
}