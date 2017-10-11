<?php

namespace App\Http\Controllers;

use App\Corporation;
use Illuminate\Http\Request;

class CorporationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('view-corporations');

        return Corporation::all();
    }
}
