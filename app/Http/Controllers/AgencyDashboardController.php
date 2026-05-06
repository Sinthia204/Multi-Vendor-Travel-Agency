<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgencyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user('agency');

        return view('agency.dashboard', compact('agency'));
    }
}
