<?php
namespace App\Variables;
$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$split = str_split($alphabet);
$letters = [];



class Procedures {
  static $types = ['LABORATORY', 'RADIOLOGY', 'CONSULTATION', 'SEND_IN', 'CORPORATE'];

  static $laboratory = ["Glucose","Cholesterol","Blood Urea Nitrogen","Creatinine","Triglycerides","Uric Acid","Lipid Profile","Kidney Profile","Liver Profile"];

  static $radiology = ["Audiometry","Chest X-ray","Routine Fecalysis","Thyroid Function Test"];

  static $consultation = ["Complete Physical Examination and Medical History","Dental Examination","Psychological Examination","Psychological Evaluation","Visual Testing and Ishihara Test/Vital Signs"];

  static $send_in = ["Glucose","Cholesterol","Blood Urea Nitrogen","Creatinine","Triglycerides","Uric Acid","Lipid Profile","Kidney Profile","Liver Profile"];

  static $corporate = ["Glucose","Cholesterol","Blood Urea Nitrogen","Creatinine","Complete Physical Examination and Medical History","Dental Examination", "Audiometry","Chest X-ray","Routine Fecalysis"];

  static $letters = [];

  static function letter($key) {
    if (array_key_exists($key, self::$letters)) {
      return self::$letters[$key];
    }

    return 'A';
  }
}

foreach ($split as $letter) {
  array_push(Procedures::$letters, $letter);
}

foreach ($split as $baseLetter) {
  foreach($split as $letter) {
      array_push(Procedures::$letters, implode('', [$baseLetter, $letter]));
  }
}
