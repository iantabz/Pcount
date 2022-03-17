<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblNavCountdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_nav_countdata', function (Blueprint $table) {
            $table->id();
            $table->string('itemcode');
            $table->string('barcode');
            $table->string('description');
            $table->string('uom');
            $table->string('qty');
            $table->string('cost_vat');
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
        Schema::dropIfExists('tbl_nav_countdata');
    }
}
