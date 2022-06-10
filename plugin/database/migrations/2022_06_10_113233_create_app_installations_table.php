<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_installations', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('domain')->nullable();
            $table->text('version')->nullable();
            $table->text('installation_path')->nullable();
            $table->text('is_symlink')->nullable();
            $table->text('is_standalone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_installations');
    }
}
