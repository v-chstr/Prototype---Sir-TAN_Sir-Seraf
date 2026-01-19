<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->onDelete('set null');
            $table->string('student_id')->nullable()->after('email');
            $table->string('employee_id')->nullable()->after('student_id');
            $table->string('phone')->nullable()->after('employee_id');
            $table->string('department')->nullable()->after('phone');
            $table->boolean('is_admin')->default(false)->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'student_id', 'employee_id', 'phone', 'department', 'is_admin']);
        });
    }
};
