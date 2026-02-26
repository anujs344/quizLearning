<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOptionsAddPoints extends Migration
{
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            if (!Schema::hasColumn('options', 'points')) {
                $table->integer('points')->default(0)->after('text');
            }
            if (Schema::hasColumn('options', 'value')) {
                $table->dropColumn('value');
            }
        });
    }

    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            if (!Schema::hasColumn('options', 'value')) {
                $table->decimal('value', 10, 2)->nullable()->after('text');
            }
            if (Schema::hasColumn('options', 'points')) {
                $table->dropColumn('points');
            }
        });
    }
}
