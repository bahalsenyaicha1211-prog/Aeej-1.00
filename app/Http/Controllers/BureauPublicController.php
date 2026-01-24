<?php

namespace App\Http\Controllers;

use App\Models\BureauMembre;

class BureauPublicController extends Controller
{
    public function index()
    {
        $bureau = BureauMembre::with(['membre'])
            ->where('is_actif', true)
            ->orderBy('ordre')
            ->get();

        return view('bureau', compact('bureau'));
    }
}
