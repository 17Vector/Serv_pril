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
        Schema::create('user_and_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->timestamp('created_at')->nullable(false)->useCurrent()->comment('Время создания записи');
            $table->integer('created_by')->nullable(false)->comment('Идентификатор пользователя создавшего запись');
            $table->timestamp('updated_at')->nullable()->useCurrent()->comment('Время обновления записи');
            $table->integer('updated_by')->nullable()->comment('Идентификатор пользователя обновившего запись');
            $table->timestamp('deleted_at')->nullable()->comment('Время мягкого удаления записи');
            $table->integer('deleted_by')->nullable()->comment('Идентификатор пользователя удалившего запись');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_and_roles');
    }
};
