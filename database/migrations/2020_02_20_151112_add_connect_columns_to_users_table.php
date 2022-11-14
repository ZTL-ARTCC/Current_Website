<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConnectColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roster', function (Blueprint $table) {            
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->unsignedBigInteger('token_expires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = array('token_expires','refresh_token','access_token');
        foreach($columns as $column) {
        if (Schema::hasColumn('roster', $column)) {
            Schema::table('roster', function (Blueprint $table) {
                $table->dropColumn($column);
            });
        }
    }

}
