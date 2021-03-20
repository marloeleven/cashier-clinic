<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller {

    public function index() {
      // echo Artisan::call("backup", []);
      exec("php artisan backup");
    }
}
