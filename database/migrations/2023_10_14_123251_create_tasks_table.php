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
        Schema::create('email_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('running');
            $table->string('email_type');
            $table->string('email_list');
            $table->dateTime('started_at');
            $table->timestamps();
        });
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id');
            $table->foreignId('email_address_id');
            $table->dateTime('send_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
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
