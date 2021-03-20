<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\PatientRecord;
use App\Patient;
use App\Http\Reports\GeneratePdf;
use tibonilab\Pdf\PdfFacade as PDF;


class ReportPDFsController extends Controller {

    private $hasLaboratory = true;

    public function show(Request $request, $id) {
        $data = PatientRecord::fetch($id);
        $pdf = new GeneratePdf($data);
        
	    return PDF::load($pdf->show(), [0, 0, 685, 450], 'portrait')->show();
    }
}
