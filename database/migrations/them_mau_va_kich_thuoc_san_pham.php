<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'colors')) {
                    $table->string('colors')->nullable()->after('unit');
                }
                if (!Schema::hasColumn('products', 'sizes')) {
                    $table->string('sizes')->nullable()->after('colors');
                }
            });
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (!Schema::hasColumn('cart_items', 'color')) {
                    $table->string('color')->nullable()->after('quantity');
                }
                if (!Schema::hasColumn('cart_items', 'size')) {
                    $table->string('size')->nullable()->after('color');
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'color')) {
                    $table->string('color')->nullable()->after('price');
                }
                if (!Schema::hasColumn('order_items', 'size')) {
                    $table->string('size')->nullable()->after('color');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['colors', 'sizes']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['color', 'size']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['color', 'size']);
        });
    }
};
