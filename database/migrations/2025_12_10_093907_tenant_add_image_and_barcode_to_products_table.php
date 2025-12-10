<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('stock');
            $table->string('image_alt')->nullable()->after('image_path');
            $table->string('barcode')->nullable()->after('image_alt');
            $table->unique('barcode'); // O use index() si no quiere uniqueness global
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['barcode']);
            $table->dropColumn(['barcode', 'image_alt', 'image_path']);
        });
    }
};
