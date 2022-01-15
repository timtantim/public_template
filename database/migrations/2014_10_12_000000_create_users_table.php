<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->id();
            // $table->string('name')->comment('姓名');
            // $table->longText('api_token')->nullable()->comment('API Token');
            // $table->longText('refresh_token')->nullable()->comment('API Refresh Token');
            // $table->string('email')->unique()->comment('Email');
            // $table->timestamp('email_verified_at')->nullable()->comment('Email 驗證');
            // $table->string('password')->comment('密碼');
            // $table->rememberToken();
            // $table->timestamps();

            $table->id();
            $table->string('name')->comment('名稱');
            $table->longText('third_party_api_token')->nullable()->comment('Third Party Api Token');
            $table->longText('third_party_api_refresh_token')->nullable()->comment('Third Party Api Refresh Token');
            $table->longText('api_token')->nullable()->comment('Api Token');
            $table->longText('refresh_token')->nullable()->comment('Api refresh_token');
            $table->string('verify_code','6')->nullable()->comment('驗證代碼，共五碼');
            $table->timestamp('verify_code_due_time')->nullable()->comment('驗證代碼有效期限');
            $table->string('email')->unique()->comment('Email');
            $table->timestamp('email_verified_at')->nullable()->comment('Email 驗證');
            $table->string('password')->comment('密碼');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
