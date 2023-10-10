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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->string('group_1')->nullable();
            $table->string('group_2')->nullable();
            $table->string('description', 2000)->nullable();
            $table->string('general_classification')->nullable();
            $table->string('item_type')->nullable();
            $table->string('unit')->nullable();
            $table->integer('qty')->nullable()->unsigned();
            $table->decimal('unit_price', 18, 2)->nullable();
            $table->decimal('global_price', 18, 2)->nullable();
            $table->string('stage')->nullable();
            $table->decimal('real_value', 18, 2)->nullable();
            $table->decimal('committed')->nullable();
            $table->integer('percentage')->nullable();
            $table->decimal('executed_dollars', 18, 2)->nullable();
            $table->decimal('executed_euros', 18, 2)->nullable();
            $table->string('supplier', 500)->nullable();
            $table->string('code', 500)->nullable();
            $table->string('order_no')->nullable();
            $table->string('input_num')->nullable();
            $table->string('observations', 2000)->nullable();
            $table->timestamps();

            $table->foreign('project_id') // Define la clave foránea
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
