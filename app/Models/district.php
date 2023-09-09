<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class district extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'districts';

    protected $fillable = [
        "id",
        "name",
        "status",
    ];
}
