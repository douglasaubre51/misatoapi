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
        Schema::create("clients", function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects");

            $table->string("client_id")->unique();

            $table->string("boot_time")->nullable();
            $table->string("location")->nullable();
            $table->string("coordinates")->nullable();
            $table->boolean("is_authorized");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("clients");
    }
};
