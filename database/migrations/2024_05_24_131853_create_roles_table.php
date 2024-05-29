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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('encryption')->unique();
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
        Schema::dropIfExists('roles');
    }
};
