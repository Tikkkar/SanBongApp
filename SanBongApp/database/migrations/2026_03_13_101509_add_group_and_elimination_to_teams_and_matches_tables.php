<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('group_name')->nullable()->after('name')->comment('Tên bảng đấu (VD: A, B, C...)');
            $table->boolean('is_eliminated')->default(false)->after('status')->comment('Đánh dấu đội đã bị loại khỏi giải');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->string('round_name')->nullable()->after('away_team_id')->comment('Tên vòng đấu (VD: Vòng bảng, Tứ kết...)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['group_name', 'is_eliminated']);
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('round_name');
        });
    }
};
