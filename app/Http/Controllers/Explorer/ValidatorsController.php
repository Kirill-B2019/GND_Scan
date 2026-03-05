<?php

namespace App\Http\Controllers\Explorer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ValidatorsController extends Controller
{
    public function index(): View
    {
        return view('explorer.validators', ['validators' => []]);
    }
}
