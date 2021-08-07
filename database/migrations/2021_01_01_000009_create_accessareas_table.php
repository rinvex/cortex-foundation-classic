<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('cortex.foundation.tables.accessareas'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_scoped')->default(true);
            $table->boolean('is_obscured')->default(true);
            $table->boolean('is_indexable')->default(true);
            $table->boolean('is_protected')->default(false);
            $table->string('prefix')->nullable();
            $table->auditableAndTimestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('cortex.foundation.tables.accessareas'));
    }
}
