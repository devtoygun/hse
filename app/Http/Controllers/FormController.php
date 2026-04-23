<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        return view('app.form.index');
    }

    public function archive(): View
    {
        return view('app.form.archive');
    }

    public function create(): View
    {
        return view('app.form.new-form');
    }

    public function createSubform(): View
    {
        return view('app.form.new-subform');
    }

    public function attach(): View
    {
        return view('app.form.form-attachement');
    }

    public function list(): View
    {
        return view('app.form.list');
    }
}

