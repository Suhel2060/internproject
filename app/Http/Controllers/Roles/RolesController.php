<?php

namespace App\Http\Controllers\Roles;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Exception;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{

    public function __construct(){
        $this->middleware('permission:view')->only(['index']);
        $this->middleware('permission:create')->only(['create']);
        $this->middleware('permission:delete')->only(['delete']);
    }
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
public function destroy($id){
    try{
        $userrole = Role::with('permissions')->findOrFail($id);
        $permissions = $userrole->permissions->pluck('name')->toArray();
        foreach($permissions as $permission){
            $userrole->revokePermissionTo($permission);
        }
        $userrole->delete();
        

        // return response()->json([$userrole]);
        return response()->json(["message"=>"Role Deleted Successfully","data"=>Role::all()]);

    }catch(Exception $e){
        return response()->json(["message"=>$e->getMessage()]);
    }

}

}

