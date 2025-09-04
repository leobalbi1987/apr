<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id('member_id');
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('middlename', 100)->nullable();
            $table->string('address', 100);
            $table->string('email', 100);
            $table->string('contact_no', 100);
            $table->integer('age');
            $table->string('gender', 100);
            $table->string('username', 100)->unique();
            $table->string('password', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
