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
        Schema::create('logs_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            
            $table->string('url');
            $table->string('http_method');

            $table->string('controller');
            $table->string('controller_action');

            $table->text('request_body')->nullable();
            $table->text('request_header')->nullable();
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->ipAddress('ip_user');
            $table->string('user_agent')->nullable();

            $table->integer('answer_status');
            $table->text('answer_body')->nullabe();
            $table->text('answer_header')->nullable();
            
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_requests');
    }
};
