<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'initial_stock')) {
                $table->unsignedInteger('initial_stock')->default(0)->after('stock');
            }
        });

        DB::table('products')->where('initial_stock', 0)->update([
            'initial_stock' => DB::raw('stock'),
        ]);
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'initial_stock')) {
                $table->dropColumn('initial_stock');
            }
        });
    }
};
