<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Http\ACL\Roles;

class AddDefaultAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userData = [
            'name' => 'admin',
            'phone_number' => '0712345678',
            'email' => 'admin@test.com',
            'active' => 1,
            'password' => Hash::make('admin')
        ];
        $user = User::create($userData);
        $user->assignRole(Roles::$ADMIN);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
