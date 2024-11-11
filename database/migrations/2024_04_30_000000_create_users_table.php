<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('taxidentification');
            $table->string('adresse');
            $table->string('password');
            $table->rememberToken();
            $table->foreign('organization_id')
            ->references('id')
            ->on('organizations')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('role')->default('user');
        });

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'phone' => '12345678',
            'adresse' => 'Admin Address',
            'taxidentification' => '123456',
            'role' => 'admin',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

