<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
public function index(){
    $roles=Role::with('permissions')->get();
    $permissions=Permission::all();
    return view('roles.index',compact('roles','permissions'));
}

public function create(Request $request){
    $data=$request->validate([
        'role'=>'required',
        'permissions'=>'required'
    ]);
    

    $role=Role::create(['name'=>$request->role]);
    $role->syncPermissions($data['permissions']);
    
    return response()->json(["data"=>Role::with('permissions')->get(),"message"=>"Role entered Successfully"]);

}
}
