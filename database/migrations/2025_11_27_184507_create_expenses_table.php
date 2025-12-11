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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // optional: später Multi-User; vorerst nullable lassen
            $table->unsignedBigInteger('user_id')->nullable();

            // Kategorie optional (z. B. am Anfang noch nicht gesetzt)
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            // Debit-Konto (z. B. Girokonto, Wallet, Bar)
            $table->foreignId('debit_account_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);          // Betrag
            $table->date('date');                                  // Datum der Ausgabe
            $table->string('vendor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
