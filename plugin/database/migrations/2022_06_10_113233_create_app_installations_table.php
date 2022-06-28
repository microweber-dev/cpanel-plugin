<?php

use App\Models\AppInstallation;
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
            $table->text('domain')->nullable();
            $table->text('user')->nullable();
            $table->text('owner')->nullable();
            $table->text('server_alias')->nullable();
            $table->text('server_admin')->nullable();
            $table->text('server_name')->nullable();
            $table->text('ip')->nullable();
            $table->text('document_root')->nullable();
            $table->text('home_dir')->nullable();
            $table->text('path')->nullable();
            $table->text('url')->nullable();
            $table->integer('is_symlink')->nullable();
            $table->integer('is_standalone')->nullable();
            $table->text('symlink_target')->nullable();
            $table->text('port')->nullable();
            $table->text('type')->nullable();
            $table->text('group')->nullable();
            $table->text('version')->nullable();
            $table->text('php_version')->nullable();
            $table->text('template')->nullable();
            $table->longText('database_details')->nullable();
            $table->longText('supported_modules')->nullable();
            $table->longText('supported_templates')->nullable();
            $table->longText('supported_languages')->nullable();
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
