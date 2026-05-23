<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password')->index();
            }

            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }

            if (! Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }

            if (! Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('last_login_ip');
            }

            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('settings', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('description')->index();
            }
        });

        Schema::table('student_parents', function (Blueprint $table): void {
            if (! Schema::hasColumn('student_parents', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('student_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_parents', function (Blueprint $table): void {
            if (Schema::hasColumn('student_parents', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });

        Schema::table('settings', function (Blueprint $table): void {
            if (Schema::hasColumn('settings', 'is_public')) {
                $table->dropColumn('is_public');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            foreach (['is_active', 'last_login_at', 'last_login_ip', 'must_change_password', 'deleted_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
