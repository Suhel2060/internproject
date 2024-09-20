<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestMiddlewareController extends Controller
{
    public function test(Request $request){
        return response()->json([$request]);
    }
}
