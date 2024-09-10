<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
public function create(Request $request){
    $request->validate([
        'permission'=>'required'
    ]);
    Permission::create(['name'=>$request->permission]);
    return response()->json(["data"=>Permission::all(),"message"=>"Role entered Successfully"]);

}
}
