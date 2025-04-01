<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class IdCardController extends Controller
{
    public function generateIdCard()
    {
        $user = Auth::user();
        $user->load('roles', 'department');

        // Render the Blade view as a string
        $html = view('id-card.idcard', compact('user'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to mPDF
        $mpdf->WriteHTML($html);

        // Output the PDF as a download
        $mpdf->Output("ID_Card_{$user->id}.pdf", "D"); // "D" forces download

        exit; // Stop further Laravel processing
    }
}
