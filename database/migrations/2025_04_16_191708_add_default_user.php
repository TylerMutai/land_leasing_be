<?php

use App\Http\ACL\Roles;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class AddDefaultUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userData = [
            'name' => 'user',
            'phone_number' => '0712345676',
            'email' => 'user@test.com',
            'email_verified_at' => now(),
            'active' => 1,
            'password' => Hash::make('user')
        ];
        $user = User::create($userData);
        $user->assignRole(Roles::$USER);
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
