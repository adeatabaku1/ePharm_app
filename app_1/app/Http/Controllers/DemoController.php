<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    // Created by [Your Name]
    public function index()
    {
        return response()->json([
            'message' => 'This is a demo controller'
        ]);
    }
}
