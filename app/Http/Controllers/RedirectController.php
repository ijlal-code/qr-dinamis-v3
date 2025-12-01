<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;

class RedirectController extends Controller
{
    public function go($code)
    {
        $qr = DynamicQr::where('code', $code)->firstOrFail();
        return redirect()->away($qr->target_url);
    }
}
