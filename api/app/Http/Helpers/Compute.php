<?php
namespace App\Http\Helpers;

class Compute {
  public static function compute($procedures, $discount_percentage, $isSenior) {
    
    $discounted = $procedures->filter(function($procedure) {
      return $procedure->procedure_type_category->procedure_type !== 'SEND_IN';
    });

    $no_discount = $procedures->filter(function($procedure) {
      return $procedure->procedure_type_category->procedure_type === 'SEND_IN';
    });

    $discounted_total = $discounted->reduce(function($total, $procedure) {
      $total += $procedure->amount;
      return $total;
    }, 0);

    $no_discount_total = $no_discount->reduce(function($total, $procedure) {
      $total += $procedure->amount;
      return $total;
    }, 0);

    $original_amount = $discounted_total + $no_discount_total;

    // SENIOR Discount application
    $total_discounted_amount = $discounted_total * $discount_percentage;
    if ($isSenior) {
      $senior_discount_total = $discounted_total / 1.12;

      $less = $senior_discount_total * $discount_percentage;

      $total_discounted_amount = $discounted_total - ($senior_discount_total - $less);
    }

    $discounted_amount = $total_discounted_amount;

    $total_amount = $original_amount - $discounted_amount;

    return (object)[
      'original_amount' => $original_amount,
      'discounted_amount' => $discounted_amount,
      'total_amount' => $total_amount,
    ];
  }


}