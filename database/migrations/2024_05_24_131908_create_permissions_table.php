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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Наименование разрешения');
            $table->string('description')->nullable()->comment('Описание разрешения');
            $table->string('encryption')->unique()->comment('Шифр разрешения');
            
            $table->timestamp('created_at')->nullable(false)->useCurrent()->comment('Время создания записи');
            $table->integer('created_by')->nullable(false)->comment('Идентификатор пользователя создавшего запись');
            $table->timestamp('deleted_at')->nullable()->comment('Время мягкого удаления записи');
            $table->integer('deleted_by')->nullable()->comment('Идентификатор пользователя удалившего запись');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
