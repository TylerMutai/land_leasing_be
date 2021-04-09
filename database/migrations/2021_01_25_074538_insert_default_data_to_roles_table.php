<?php

use App\Http\ACL\Roles;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertDefaultDataToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert default values to DB
        DB::table('roles')->insert(
            [
                [
                    'name' => Roles::$LESSOR,
                    'guard_name' => 'api',
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'name' => Roles::$USER,
                    'guard_name' => 'api',
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'name' => Roles::$MERCHANT,
                    'guard_name' => 'api',
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ],
                [
                    'name' => Roles::$ADMIN,
                    'guard_name' => 'api',
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]

            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
}
