<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblItemMasterfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_masterfile', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('uom');
            $table->string('barcode');
            $table->string('desc');
            $table->string('extended_desc');
            $table->string('vendor_code');
            $table->string('section');
            $table->string('category');
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
        Schema::dropIfExists('tbl_item_masterfile');
    }
}
