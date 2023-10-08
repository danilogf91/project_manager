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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pda_code');
            $table->integer('data_uploaded')->default(0);;
            $table->float('rate');
            $table->enum('state', ['planification', 'execution', 'finished'])->default('planification');
            $table->enum('investments', [
                'innovation',
                'efficiency_&_saving',
                'replacement_&_restructuring',
                'quality_&_hygiene',
                'health_&_safety',
                'environment',
                'maintenance',
                'capacity_increase'
            ])->default('innovation');
            $table->enum('justification', ['normal_capex', 'special_project'])->default('normal_capex');
            $table->date('start_date');
            $table->date('finish_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
