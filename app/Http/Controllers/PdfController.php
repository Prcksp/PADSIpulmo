<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        $rppm = \App\Models\Rppm::findOrFail($request->id);

        $pdf = PDF::loadView('pdf.rppm', ['rppm' => $rppm]);

        return $pdf->download('rppm_content.pdf');
    }
}
