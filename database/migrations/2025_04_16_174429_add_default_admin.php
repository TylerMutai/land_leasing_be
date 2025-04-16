<?php

use App\Http\ACL\Roles;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

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
            'email_verified_at' => now(),
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
