<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->string('statement_path')->nullable()->after('message');
            $table->string('originality_path')->nullable()->after('statement_path');
            $table->string('cover_path')->nullable()->after('originality_path');
            $table->string('dummy_path')->nullable()->after('cover_path');
        });
    }

    public function down(): void
    {
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->dropColumn(['statement_path', 'originality_path', 'cover_path', 'dummy_path']);
        });
    }
};
