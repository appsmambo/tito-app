<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pensioner extends Model
{
    use HasFactory;
    protected $fillable = ['dni', 'cip', 'institution', 'first_name', 'last_name', 'birth_date', 'phone', 'email', 'status'];
}
