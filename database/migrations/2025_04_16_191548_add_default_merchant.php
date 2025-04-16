<?php

use App\Http\ACL\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class AddDefaultMerchant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userData = [
            'name' => 'merchant',
            'phone_number' => '0712345677',
            'email' => 'merchant@test.com',
            'email_verified_at' => now(),
            'active' => 1,
            'password' => Hash::make('merchant')
        ];
        $user = User::create($userData);
        $user->assignRole(Roles::$MERCHANT);
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
