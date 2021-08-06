<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('cortex.foundation.tables.accessibles'), function (Blueprint $table) {
            // Columns
            $table->integer('accessarea_id')->unsigned();
            $table->morphs('accessible');
            $table->timestamps();

            // Indexes
            $table->unique(['accessarea_id', 'accessible_id', 'accessible_type'], 'accessibles_ids_type_unique');
            $table->foreign('accessarea_id')->references('id')->on(config('cortex.foundation.tables.accessareas'))
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('cortex.foundation.tables.accessibles'));
    }
}
