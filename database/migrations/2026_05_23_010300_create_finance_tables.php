<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $this->auditColumns($table);
        });

        Schema::create('payment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $this->auditColumns($table);
        });

        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_category_id')->constrained()->restrictOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bill_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table);
        });

        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('student_payments')->nullOnDelete();
            $table->string('payment_number')->unique();
            $table->date('paid_at');
            $table->decimal('amount', 15, 2);
            $table->string('method')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table, 'draft');
        });

        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('cash_transactions')->nullOnDelete();
            $table->string('transaction_number')->unique();
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('description')->nullable();
            $this->auditColumns($table, 'draft');
            $table->index(['source_type', 'source_id']);
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('donations')->nullOnDelete();
            $table->string('donation_number')->unique();
            $table->string('donor_name');
            $table->string('donor_phone')->nullable();
            $table->date('donated_at');
            $table->decimal('amount', 15, 2);
            $table->string('method')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $this->auditColumns($table, 'draft');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('expenses')->nullOnDelete();
            $table->string('expense_number')->unique();
            $table->string('category')->nullable();
            $table->date('spent_at');
            $table->decimal('amount', 15, 2);
            $table->string('payee')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('description')->nullable();
            $this->auditColumns($table, 'draft');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('cash_transactions');
        Schema::dropIfExists('student_payments');
        Schema::dropIfExists('student_bills');
        Schema::dropIfExists('payment_categories');
        Schema::dropIfExists('cash_accounts');
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
