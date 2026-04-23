<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FacilityController extends Controller
{
    public function listFacilities(): View
    {
        return view('app.facility.list-facility');
    }

    public function createUnit(): View
    {
        return view('app.facility.new-unit');
    }

    public function createFacility(): View
    {
        return view('app.facility.new-facility');
    }
}

