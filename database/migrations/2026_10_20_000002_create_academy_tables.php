<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Groupes d'entraînement (U7, U9, ...). Nom "training_groups" car GROUPS est un mot réservé MySQL.
        Schema::create('training_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 10);
            $table->unsignedTinyInteger('age_min');
            $table->unsignedTinyInteger('age_max');
            $table->string('schedule')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('capacity')->default(25);
            $table->decimal('price', 10, 3)->default(0); // dinars tunisiens : 3 décimales (millimes)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->boolean('photo_consent')->default(false);
            $table->text('medical_notes')->nullable(); // chiffré par le modèle
            $table->timestamps();
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_group_id')->constrained()->cascadeOnDelete();
            $table->string('season', 9);
            $table->string('status', 20)->default('pending'); // pending | approved | rejected
            $table->decimal('amount', 10, 3)->default(0);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['child_id', 'training_group_id', 'season']);
            $table->index(['season', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 30);
            $table->string('provider_ref')->nullable()->unique();
            $table->decimal('amount', 10, 3);
            $table->char('currency', 3)->default('TND');
            $table->string('status', 20)->default('pending'); // pending | paid | failed
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_group_id')->constrained()->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index(['training_group_id', 'starts_at']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->boolean('present')->default(false);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['training_session_id', 'child_id']);
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_group_id')->nullable()->constrained()->cascadeOnDelete(); // null = tout le club
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('children');
        Schema::dropIfExists('training_groups');
    }
};
