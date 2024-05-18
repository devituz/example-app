<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsCodesTable extends Migration
{
    public function up()
    {
        Schema::create('sms_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('code');
            $table->string('status')->default('pending'); // Add status field with default value
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_codes');
    }
}
