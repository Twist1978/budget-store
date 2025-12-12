<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Accountname e.g. "Girokonto", "Bargeld", "Kreditkarte"
            $table->string('iban'); // IBAN of the account
            $table->string('bic'); // BIC of the account
            $table->integer('overdraft'); // the overdraft of the account
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
