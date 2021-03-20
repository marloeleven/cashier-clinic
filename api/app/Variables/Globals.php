<?php
namespace App\Variables;

class Globals {
  static $types = ['LABORATORY', 'RADIOLOGY', 'CONSULTATION', 'SEND_IN', 'CORPORATE'];
  static $typesProperNames = [
    'LABORATORY' => 'Laboratory',
    'RADIOLOGY' => 'Radiology',
    'CONSULTATION' => 'Consultation',
    'SEND_IN' => 'Send-In',
    'CORPORATE' => 'Corporate'
  ];

  static $reports = [
    'generic' => 'Generic',
    'cbc' => 'CBC',
    'chemistry' => 'Chemistry',
    'ecg' => 'ECG',
    'parasitology' => 'Parasitology',
    'serology' => 'Serology',
    'urinalysis' => 'Urinalysis',
  ];

  static $typesProceedTo = [
    'LABORATORY' => 'Please proceed to Extraction',
    'RADIOLOGY' => 'Please proceed to Radiology',
    'CONSULTATION' => 'Please proceed to Nurse\'s Station',
    'SEND_IN' => 'Please proceed to Nurse\'s Station (Send in)',
    'CORPORATE' => 'Please proceed to Nurse\'s Station (Corporate)'
  ];

  static function test($data) {
    echo "<pre>";
    print_r($data);
    exit;
  }
}