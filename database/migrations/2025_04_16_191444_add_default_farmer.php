<?php

use App\Http\ACL\Roles;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class AddDefaultFarmer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userData = [
            'name' => 'lessor',
            'phone_number' => '0712345679',
            'email' => 'lessor@test.com',
            'email_verified_at' => now(),
            'active' => 1,
            'password' => Hash::make('lessor')
        ];
        $user = User::create($userData);
        $user->assignRole(Roles::$LESSOR);
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
