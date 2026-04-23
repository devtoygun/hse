<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(): View
    {
        return view('app.index');
    }

    public function setLocale(Request $request, string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['tr', 'en'], true), 404);

        $request->session()->put('locale', $locale);

        return redirect()->to(url()->previous() ?: route('app.index'));
    }
}
