<?php

namespace App\Filament\Support;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\AttendanceSession;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\PaymentCategory;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramClass;
use App\Models\ProgramLevel;
use App\Models\ProgramMaterial;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBill;
use App\Models\StudentParent;
use App\Models\StudentPayment;
use App\Models\StudentProgram;
use App\Models\StudentProgress;
use App\Models\StudentSavingsAccount;
use App\Models\StudentSavingsTransaction;
use App\Models\StudentScore;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ResourceDefaults
{
    /**
     * Generate default StudentSavingsTransaction number:
     * TPQ-AH\SV\YYYYMMDD{SEQ}
     */
    private static function generateStudentSavingsTransactionNumber(): string
    {
        $datePart = now()->format('Ymd');
        $prefix = "TPQ-AH\\SV\\{$datePart}";

        $last = \App\Models\StudentSavingsTransaction::query()
            ->where('transaction_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $seq = 1;
        if ($last) {
            $transactionNumber = (string) $last->transaction_number;
            if (preg_match('/' . preg_quote($prefix, '/') . '([0-9]+)$/', $transactionNumber, $m)) {
                $seq = (int) $m[1] + 1;
            } else {
                $seq = 1;
            }
        }

        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    public static function group(string $model): string
    {
        return match ($model) {
            Student::class, StudentParent::class, Teacher::class, AcademicYear::class => 'Master Data',
            ProgramCategory::class, Program::class, ProgramLevel::class, ProgramClass::class, ProgramMaterial::class,
            StudentProgram::class, StudentProgress::class, StudentScore::class => 'Pendidikan',
            AttendanceSession::class, StudentAttendance::class, TeacherAttendance::class => 'Absensi',
            StudentBill::class, StudentPayment::class, CashTransaction::class, Donation::class, Expense::class => 'Keuangan',
            StudentSavingsAccount::class, StudentSavingsTransaction::class => 'Tabungan',
            PostCategory::class, Post::class, Announcement::class, Gallery::class, Page::class => 'Website',
            default => 'Data',
        };
    }

    public static function titleAttribute(string $model): string
    {
        return match ($model) {
            AcademicYear::class, ProgramCategory::class, Program::class, ProgramLevel::class, ProgramClass::class,
            Student::class, StudentParent::class, Teacher::class, CashAccount::class, PaymentCategory::class => 'name',
            StudentBill::class => 'bill_number',
            StudentPayment::class => 'payment_number',
            CashTransaction::class, StudentSavingsTransaction::class => 'transaction_number',
            Donation::class => 'donation_number',
            Expense::class => 'expense_number',
            StudentSavingsAccount::class => 'account_number',
            Post::class, Announcement::class, Gallery::class, Page::class, ProgramMaterial::class => 'title',
            default => 'id',
        };
    }

    public static function formComponents(string $model): array
    {
        return match ($model) {
            Student::class => [
                self::rel('branch_id', 'branch'),
                self::text('student_number', true)->unique(ignoreRecord: true),
                self::text('nisn'),
                self::text('name', true),
                self::select('gender', ['male' => 'Laki-laki', 'female' => 'Perempuan'])->required(),
                self::text('birth_place'),
                DatePicker::make('birth_date'),
                DatePicker::make('enrolled_at'),
                self::text('phone')->tel(),
                Textarea::make('address')->columnSpanFull(),
                self::status(),
            ],
            StudentParent::class => [
                self::rel('student_id', 'student', 'name')->required(),
                self::text('name', true),
                self::text('relationship', true),
                self::text('phone')->tel(),
                self::text('email')->email(),
                self::text('occupation'),
                Textarea::make('address')->columnSpanFull(),
                self::status(),
            ],
            Teacher::class => [
                self::rel('branch_id', 'branch'),
                self::text('employee_number')->unique(ignoreRecord: true),
                self::text('name', true),
                self::select('gender', ['male' => 'Laki-laki', 'female' => 'Perempuan']),
                self::text('phone')->tel(),
                self::text('email')->email(),
                DatePicker::make('joined_at'),
                Textarea::make('address')->columnSpanFull(),
                self::status(),
            ],
            AcademicYear::class => [
                self::rel('branch_id', 'branch'),
                self::text('name', true),
                DatePicker::make('start_date')->required(),
                DatePicker::make('end_date')->required(),
                Toggle::make('is_active'),
                self::status(),
            ],
            ProgramCategory::class, PostCategory::class => [
                self::text('name', true),
                self::text('slug', true)->unique(ignoreRecord: true),
                Textarea::make('description')->columnSpanFull(),
                self::status(),
            ],
            Program::class => [
                self::rel('program_category_id', 'category')->required(),
                self::text('name', true),
                self::text('slug', true)->unique(ignoreRecord: true),
                TextInput::make('sort_order')->numeric()->default(0),
                Textarea::make('description')->columnSpanFull(),
                self::status(),
            ],
            ProgramLevel::class => [
                self::rel('program_id', 'program')->required(),
                self::text('name', true),
                TextInput::make('level_order')->numeric()->default(0),
                Textarea::make('description')->columnSpanFull(),
                self::status(),
            ],
            ProgramClass::class => [
                self::rel('branch_id', 'branch'),
                self::rel('academic_year_id', 'academicYear'),
                self::rel('program_level_id', 'programLevel')->required(),
                self::rel('teacher_id', 'teacher'),
                self::text('name', true),
                self::text('code'),
                TextInput::make('capacity')->numeric(),
                Textarea::make('schedule_notes')->columnSpanFull(),
                self::status(),
            ],
            StudentProgram::class => [
                self::rel('student_id', 'student')->required(),
                self::rel('program_class_id', 'programClass')->required(),
                DatePicker::make('joined_at'),
                DatePicker::make('completed_at'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            StudentProgress::class => [
                self::rel('student_program_id', 'studentProgram', 'id')->required(),
                self::rel('program_material_id', 'programMaterial', 'title')->required(),
                TextInput::make('progress_percent')->numeric()->minValue(0)->maxValue(100)->default(0),
                DatePicker::make('completed_at'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            StudentScore::class => [
                self::rel('student_program_id', 'studentProgram', 'id')->required(),
                self::rel('program_material_id', 'programMaterial', 'title'),
                self::text('assessment_type'),
                TextInput::make('score')->numeric(),
                self::text('grade'),
                DatePicker::make('assessed_at'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            AttendanceSession::class => [
                self::rel('branch_id', 'branch'),
                self::rel('program_class_id', 'programClass'),
                self::rel('teacher_id', 'teacher'),
                DatePicker::make('session_date')->required(),
                self::text('started_at'),
                self::text('ended_at'),
                self::text('topic'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            StudentAttendance::class => [
                self::rel('attendance_session_id', 'attendanceSession', 'id')->required(),
                self::rel('student_id', 'student')->required(),
                self::attendanceStatus(),
                self::text('checked_in_at'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            TeacherAttendance::class => [
                self::rel('attendance_session_id', 'attendanceSession', 'id')->required(),
                self::rel('teacher_id', 'teacher')->required(),
                self::attendanceStatus(),
                self::text('checked_in_at'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            StudentBill::class => [
                self::rel('student_id', 'student')->required(),
                self::rel('payment_category_id', 'paymentCategory')->required(),
                self::rel('academic_year_id', 'academicYear'),
                self::text('bill_number', true)->unique(ignoreRecord: true),
                self::money('amount')->required(),
                self::money('paid_amount')->default(0),
                DatePicker::make('due_date'),
                Textarea::make('notes')->columnSpanFull(),
                self::status(),
            ],
            StudentPayment::class => [
                self::rel('student_bill_id', 'studentBill', 'bill_number')->required(),
                self::rel('cash_account_id', 'cashAccount'),
                self::text('payment_number', true)->unique(ignoreRecord: true),
                DatePicker::make('paid_at')->required(),
                self::money('amount')->required(),
                self::text('method'),
                self::transactionStatus(),
                DateTimePicker::make('posted_at'),
                Textarea::make('notes')->columnSpanFull(),
            ],
            CashTransaction::class => [
                self::rel('cash_account_id', 'cashAccount')->required(),
                self::text('transaction_number', true)->unique(ignoreRecord: true),
                self::select('type', ['income' => 'Income', 'expense' => 'Expense', 'transfer' => 'Transfer'])->required(),
                DatePicker::make('transaction_date')->required(),
                self::money('amount')->required(),
                self::text('source_type'),
                TextInput::make('source_id')->numeric(),
                self::transactionStatus(),
                DateTimePicker::make('posted_at'),
                Textarea::make('description')->columnSpanFull(),
            ],
            Donation::class => [
                self::rel('cash_account_id', 'cashAccount'),
                self::text('donation_number', true)->unique(ignoreRecord: true),
                self::text('donor_name', true),
                self::text('donor_phone')->tel(),
                DatePicker::make('donated_at')->required(),
                self::money('amount')->required(),
                self::text('method'),
                self::transactionStatus(),
                DateTimePicker::make('posted_at'),
                Textarea::make('notes')->columnSpanFull(),
            ],
            Expense::class => [
                self::rel('cash_account_id', 'cashAccount'),
                self::text('expense_number', true)->unique(ignoreRecord: true),
                self::text('category'),
                DatePicker::make('spent_at')->required(),
                self::money('amount')->required(),
                self::text('payee'),
                self::transactionStatus(),
                DateTimePicker::make('posted_at'),
                Textarea::make('description')->columnSpanFull(),
            ],
            StudentSavingsAccount::class => [
                self::rel('student_id', 'student')->required(),
                self::text('account_number', true)->unique(ignoreRecord: true),
                DatePicker::make('opened_at'),
                DatePicker::make('closed_at'),
                self::status(),
            ],
            StudentSavingsTransaction::class => [
                self::rel('student_savings_account_id', 'studentSavingsAccount', 'account_number')->required(),

                // Default transaction number format: TPQ-AH\SV\YYYYMMDD{SEQ}
                self::text('transaction_number', true)->unique(ignoreRecord: true)
                    ->default(fn (): string => static::generateStudentSavingsTransactionNumber()),

                self::select('type', ['deposit' => 'Deposit', 'withdrawal' => 'Withdrawal', 'adjustment' => 'Adjustment'])->required(),
                DatePicker::make('transaction_date')->required()->default(fn () => now()->toDateString()),

                self::money('amount')->required(),
                self::money('balance_after')->disabled()->dehydrated(false),
                self::transactionStatus(),
                DateTimePicker::make('posted_at'),
                Textarea::make('notes')->columnSpanFull(),
            ],
            Post::class => [
                self::rel('post_category_id', 'category'),
                self::text('title', true),
                self::text('slug', true)->unique(ignoreRecord: true),
                self::text('featured_image'),
                Textarea::make('excerpt')->columnSpanFull(),
                RichEditor::make('content')->columnSpanFull(),
                DateTimePicker::make('published_at'),
                self::status(),
            ],
            Announcement::class => [
                self::text('title', true),
                RichEditor::make('content')->columnSpanFull(),
                DatePicker::make('starts_at'),
                DatePicker::make('ends_at'),
                Toggle::make('is_pinned'),
                self::status(),
            ],
            Gallery::class => [
                self::text('title', true),
                self::text('image_path', true),
                Textarea::make('caption')->columnSpanFull(),
                TextInput::make('sort_order')->numeric()->default(0),
                self::status(),
            ],
            Page::class => [
                self::text('title', true),
                self::text('slug', true)->unique(ignoreRecord: true),
                RichEditor::make('content')->columnSpanFull(),
                DateTimePicker::make('published_at'),
                self::status(),
            ],
            default => [
                self::text('name'),
                self::status(),
            ],
        };
    }

    public static function tableColumns(string $model): array
    {
        return collect(self::columnsFor($model))
            ->map(fn (string $column) => self::tableColumn($column))
            ->all();
    }

    public static function infolistComponents(string $model): array
    {
        return collect(self::columnsFor($model))
            ->map(fn (string $column) => TextEntry::make($column))
            ->all();
    }

    private static function columnsFor(string $model): array
    {
        return match ($model) {
            Student::class => ['student_number', 'name', 'gender', 'status'],
            StudentParent::class => ['student.name', 'name', 'relationship', 'phone', 'status'],
            Teacher::class => ['employee_number', 'name', 'phone', 'status'],
            AcademicYear::class => ['name', 'start_date', 'end_date', 'is_active', 'status'],
            ProgramCategory::class, Program::class, ProgramLevel::class, ProgramClass::class => ['name', 'status'],
            ProgramMaterial::class => ['title', 'material_order', 'status'],
            StudentProgram::class => ['student.name', 'programClass.name', 'joined_at', 'status'],
            StudentProgress::class => ['studentProgram.id', 'programMaterial.title', 'progress_percent', 'status'],
            StudentScore::class => ['studentProgram.id', 'score', 'grade', 'status'],
            AttendanceSession::class => ['session_date', 'programClass.name', 'teacher.name', 'status'],
            StudentAttendance::class => ['attendanceSession.session_date', 'student.name', 'attendance_status', 'status'],
            TeacherAttendance::class => ['attendanceSession.session_date', 'teacher.name', 'attendance_status', 'status'],
            StudentBill::class => ['bill_number', 'student.name', 'amount', 'paid_amount', 'status'],
            StudentPayment::class => ['payment_number', 'studentBill.bill_number', 'amount', 'paid_at', 'status'],
            CashTransaction::class => ['transaction_number', 'type', 'amount', 'transaction_date', 'status'],
            Donation::class => ['donation_number', 'donor_name', 'amount', 'donated_at', 'status'],
            Expense::class => ['expense_number', 'category', 'amount', 'spent_at', 'status'],
            StudentSavingsAccount::class => ['account_number', 'student.name', 'balance', 'status'],
            StudentSavingsTransaction::class => ['transaction_number', 'studentSavingsAccount.account_number', 'type', 'amount', 'status'],
            Post::class, Page::class => ['title', 'slug', 'published_at', 'status'],
            PostCategory::class => ['name', 'slug', 'status'],
            Announcement::class => ['title', 'starts_at', 'ends_at', 'is_pinned', 'status'],
            Gallery::class => ['title', 'image_path', 'sort_order', 'status'],
            default => ['id', 'status'],
        };
    }

    private static function tableColumn(string $column): TextColumn|IconColumn
    {
        if (in_array($column, ['is_active', 'is_pinned'], true)) {
            return IconColumn::make($column)->boolean();
        }

        return TextColumn::make($column)
            ->searchable(str_contains($column, '.') ? false : true)
            ->sortable(str_contains($column, '.') || $column === 'balance' ? false : true)
            ->toggleable();
    }

    private static function text(string $name, bool $required = false): TextInput
    {
        $input = TextInput::make($name)->maxLength(255);

        return $required ? $input->required() : $input;
    }

    private static function money(string $name): TextInput
    {
        return TextInput::make($name)->numeric()->prefix('Rp');
    }

    private static function rel(string $name, string $relationship, string $title = 'name'): Select
    {
        return Select::make($name)
            ->relationship($relationship, $title)
            ->searchable()
            ->preload();
    }

    private static function select(string $name, array $options): Select
    {
        return Select::make($name)->options($options);
    }

    private static function status(): Select
    {
        return self::select('status', [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])->default('active')->required();
    }

    private static function transactionStatus(): Select
    {
        return self::select('status', [
            'draft' => 'Draft',
            'posted' => 'Posted',
            'cancelled' => 'Cancelled',
        ])->default('draft')->required();
    }

    private static function attendanceStatus(): Select
    {
        return self::select('attendance_status', [
            'present' => 'Hadir',
            'absent' => 'Tidak hadir',
            'sick' => 'Sakit',
            'permission' => 'Izin',
            'late' => 'Terlambat',
        ])->default('present')->required();
    }
}
