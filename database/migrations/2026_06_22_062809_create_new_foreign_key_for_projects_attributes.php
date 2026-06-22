<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("Attributes", function (Blueprint $table) {
            $table->dropForeign("attributes_project_id_foreign");

            $table
                ->foreign("project_id")
                ->references("id")
                ->on("projects")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("Attributes", function (Blueprint $table) {
            //
        });
    }
};
