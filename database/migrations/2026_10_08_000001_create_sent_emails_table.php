<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // سجل الإيميلات اللي انبعتت من لوحة التحكم
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('body');
            $table->string('audience', 20); // all | role | users
            $table->string('audience_role')->nullable();
            $table->unsignedInteger('recipients_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
