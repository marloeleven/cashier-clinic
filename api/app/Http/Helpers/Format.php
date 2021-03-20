<?php
namespace App\Http\Helpers;

class Format {
  public static function DateDisplay($date) {
    return date('F j, Y', strtotime($date));
  }

  public static function procedureStringLimit($string, $limit = 40) {
    return strlen($string) > $limit ? substr($string, 0, $limit - 3) . '...' : $string;
  }
}