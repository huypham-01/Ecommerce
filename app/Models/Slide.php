<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Slide extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;
    protected $fillable = [
        'name',
        'description',
        'keyword',
        'setting',
        'item',
        'short_code',
        'publish',
    ];
    protected $table = 'slides';
    protected $casts = [
        'setting' => 'json',
        'item' => 'json',
    ];
}
