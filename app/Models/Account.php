<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'iban',
        'bic',
        'overdraft',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'debit_account_id');
    }
}
