<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class card_entity extends Model
{
    use HasFactory;

    protected  $table = 'card_entity';
    public $timestamp = false;
}
