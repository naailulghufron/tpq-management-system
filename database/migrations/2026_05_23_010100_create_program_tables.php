<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $this->auditColumns($table);
        });

        Schema::create('program_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('level_order')->default(0);
            $table->text('description')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('program_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->text('schedule_notes')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('program_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_level_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('material_order')->default(0);
            $table->string('attachment_path')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('student_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_class_id')->constrained()->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
            $table->unique(['student_id', 'program_class_id', 'deleted_at']);
        });

        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_material_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_material_id')->nullable()->constrained()->nullOnDelete();
            $table->string('assessment_type')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->string('grade')->nullable();
            $table->date('assessed_at')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('student_programs');
        Schema::dropIfExists('program_materials');
        Schema::dropIfExists('program_classes');
        Schema::dropIfExists('program_levels');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('program_categories');
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
