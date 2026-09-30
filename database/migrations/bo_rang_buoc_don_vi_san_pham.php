<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('products')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropUnique('products_unit_unique');
                });
            } catch (\Throwable $e) {
                // Ignore if index doesn't exist
            }
        }
    }

    public function down()
    {
        // No need to restore unique constraint on unit
    }
};
