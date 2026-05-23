<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_savings_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('account_number')->unique();
            $table->date('opened_at')->nullable();
            $table->date('closed_at')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('student_savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_savings_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('student_savings_transactions')->nullOnDelete();
            $table->string('transaction_number')->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'adjustment']);
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table, 'draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_savings_transactions');
        Schema::dropIfExists('student_savings_accounts');
    }

    private function auditColumns(Blueprint $table, string $defaultStatus = 'active'): void
    {
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('approved_at')->nullable();
        $table->string('status')->default($defaultStatus)->index();
        $table->timestamps();
        $table->softDeletes();
    }
};
