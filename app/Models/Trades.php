<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trades extends Model
{
    /** @use HasFactory<\Database\Factories\TradesFactory> */
    use HasFactory;
    protected $guarded = [];
}
