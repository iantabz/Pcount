<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUnposted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_unposted', function (Blueprint $table) {
            $table->id();
            $table->string('itemcode');
            $table->string('barcode');
            $table->string('description');
            $table->string('uom');
            $table->string('qty');
            $table->string('unit_cost');
            $table->string('amt');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_unposted');
    }
}
