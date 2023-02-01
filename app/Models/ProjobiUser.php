<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjobiUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql_projobi';
    protected $table = 'users';

    public function getSubscriptionActiveUntilAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setSubscriptionActiveUntilAttribute($value)
    {
        $this->attributes['subscription_active_until'] = Carbon::parse($value)->format('Y-m-d');
    }
}
