<?php

namespace App\Http\Controllers\setPermission;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class SetPermission extends Controller
{

    public function index(){
        $userswithroles=User::with('roles')->get();
        $roles=Role::all();
        $users=User::all();
 
        return view('assignpermission.index',compact('users','roles','userswithroles'));
    }

    public function create(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'userid' => 'required|exists:users,id', // Ensure user ID exists
                'roles' => 'required', // Ensure roles is an array
            ]);

            // Find the user
            $user = User::findOrFail($request->userid);

            // Fetch existing roles from the database
            $existingRoles = Role::whereIn('id', $request->roles)->pluck('name')->toArray();

            // Sync roles to the user
            $user->syncRoles($existingRoles);

            // Return success response with updated user roles
            return response()->json([
                'message' => 'Roles assigned successfully',
                'data' => User::with('roles')->get()// Return the updated user with roles
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ]);
        }
    }
}
