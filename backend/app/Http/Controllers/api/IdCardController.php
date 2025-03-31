<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class IdCardController extends Controller
{
    public function generateIdCard()
    {
        $user = Auth::user();
        $user->load('roles', 'department');
        $pdf = Pdf::loadView('id-card.idcard', compact('user'));

//        dd($pdf);

//        return view('id-card.idcard', compact('user'));
        return $pdf->download('ID_Card_'.$user->id.'.pdf');
    }
}
