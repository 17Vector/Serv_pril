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
        Schema::create('role_and_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            $table->timestamp('created_at')->nullable()->useCurrent()->comment('Время создания записи');
            $table->integer('created_by')->nullable()->comment('Идентификатор пользователя создавшего запись');
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
        Schema::dropIfExists('role_and_permissions');
    }
};
