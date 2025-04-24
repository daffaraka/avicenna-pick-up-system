<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeGenerator extends Controller
{
    public function generate(Request $request)
    {
        $qrCodes = [];
        $qrCodes['simple'] =
            QrCode::size(150)->generate('https://daffaraka.github.io/.io/');
        $qrCodes['changeColor'] =
            QrCode::size(150)->color(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
        $qrCodes['changeBgColor'] =
            QrCode::size(150)->backgroundColor(255, 0, 0)->generate('https://daffaraka.github.io/.io/');
        $qrCodes['styleDot'] =
            QrCode::size(150)->style('dot')->generate('https://daffaraka.github.io/.io/');
        $qrCodes['styleSquare'] = QrCode::size(150)->style('square')->generate('https://daffaraka.github.io/.io/');
        $qrCodes['styleRound'] = QrCode::size(150)->style('round')->generate('https://daffaraka.github.io/.io/');



        dd($qrCodes);
        return view('dashboard.qrcode', $qrCodes);
    }
}
