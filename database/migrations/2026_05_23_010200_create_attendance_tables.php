<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_class_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->date('session_date');
            $table->time('started_at')->nullable();
            $table->time('ended_at')->nullable();
            $table->string('topic')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('attendance_status')->default('present')->index();
            $table->time('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
            $table->unique(['attendance_session_id', 'student_id', 'deleted_at'], 'student_attendance_unique');
        });

        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('attendance_status')->default('present')->index();
            $table->time('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
            $table->unique(['attendance_session_id', 'teacher_id', 'deleted_at'], 'teacher_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('attendance_sessions');
    }

    private function auditColumns(Blueprint $table): void
    {
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('approved_at')->nullable();
        $table->string('status')->default('active')->index();
        $table->timestamps();
        $table->softDeletes();
    }
};
