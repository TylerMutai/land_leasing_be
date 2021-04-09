<?php

namespace App\Http\Controllers\ACL;

use App\Http\ACL\Roles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ACLController extends Controller
{
    public function create()
    {
        //Create Roles. (web)
        Role::create(['name' => Roles::$LESSOR]);
        Role::create(['name' => Roles::$MERCHANT]);
        Role::create(['name' => Roles::$USER]);
        Role::create(['name' => Roles::$ADMIN]);

        /*//Create Roles. (api)
        Role::create(['name' => Roles::$COUNSELLOR, 'guard_name' => 'api']);
        Role::create(['name' => Roles::$PATIENT, 'guard_name' => 'api']);
        Role::create(['name' => Roles::$ADMIN, 'guard_name' => 'api']);*/

        //Create Permissions:

    }
}
