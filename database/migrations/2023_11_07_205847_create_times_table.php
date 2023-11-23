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
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->date('date');
            $table->string('start')->comment('Example: 12:00');
            $table->string('end')->nullable()->comment('Example: 13:00');
            $table->bigInteger('total')->default(0);
            $table->string('jira_issue')->nullable();
            $table->string('task')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_calculate_on_sum')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};
