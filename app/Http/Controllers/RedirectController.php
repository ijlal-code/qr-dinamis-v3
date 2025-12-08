<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;

class RedirectController extends Controller
{
    public function go($code)
    {
        $qr = DynamicQr::where('code', $code)->first();

        if (! $qr) {
            abort(404, 'QR Code tidak ditemukan.');
        }

        $qr->increment('scans_count');

        return redirect()->away($qr->target_url);
    }
}
