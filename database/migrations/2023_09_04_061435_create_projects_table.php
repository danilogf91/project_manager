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
            $table->enum('state', ['Planification', 'Execution', 'Finished'])->default('Planification');
            $table->enum('investments', [
                'Innovation',
                'Efficiency & Saving',
                'Replacement & Restructuring',
                'Quality & Hygiene',
                'Health & Safety',
                'Environment',
                'Maintenance',
                'Capacity Increase'
            ])->default('Innovation');
            $table->enum('classification_of_investments', [
                'Buildings',
                'Furniture',
                'General Install',
                'Land',
                'Machines & Equipm',
                'Office Hardware Software',
                'Other',
                'Vehicles',
                'Vessel & Fishing Equipment',
                'Warenhouse & Distrib',
            ])->default('Buildings');
            $table->enum('justification', ['Normal Capex', 'Special Project'])->default('Normal Capex');
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
