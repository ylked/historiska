<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class continent extends Model
{
    use HasFactory;

    protected $table = 'continent';
    public $timestamps = false;
}
