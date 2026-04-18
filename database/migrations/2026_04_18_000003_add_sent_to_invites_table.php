<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->boolean('sent')->default(false)->after('plus_one');
        });
    }

    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('sent');
        });
    }
};
