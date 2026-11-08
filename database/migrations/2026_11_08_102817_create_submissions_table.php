<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {

            $table->id();

            // Who submitted the code?
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            // Which problem was submitted?
            $table->foreignId('problem_id')
                ->constrained()
                ->restrictOnDelete();

            // Programming language
            $table->string('language', 50);

            // Submitted source code
            $table->longText('source_code');

            // FastAPI Judge Engine job ID
            $table->string('judge_job_id')
                ->nullable()
                ->unique();

            // Execution lifecycle
            $table->string('execution_status')
                ->default('queued');

            // Final judging result
            $table->string('verdict')
                ->nullable();

            // Execution statistics
            $table->unsignedInteger('execution_time_ms')
                ->nullable();

            $table->unsignedBigInteger('memory_used_kb')
                ->nullable();

            // Test-case statistics
            $table->unsignedInteger('passed_test_cases')
                ->default(0);

            $table->unsignedInteger('total_test_cases')
                ->default(0);

            // Optional judge error information
            $table->text('error_message')
                ->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['problem_id', 'created_at']);
            $table->index(['execution_status']);
            $table->index(['verdict']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
