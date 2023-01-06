<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjobiUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql_projobi';
    protected $table = 'users';
}
