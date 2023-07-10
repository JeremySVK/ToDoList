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
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_task_id')->unsigned()->nullable();
            $table->string('title', 50);
            $table->longText('description');
            $table->timestamp('created_at')->useCurrent();
            $table->date('deadline')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->integer('priority')->unsigned()->nullable();
            $table->softDeletes();

            $table->foreign('status_id')->references('id')->on('statuses');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
