<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class Widget extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;
    
  
    protected $fillable = [
        'name',
        'keyword',
        'description',
        'model_id',
        'model',
        'short_code',
        'album',
        'publish',
    ];
    protected $table = 'widgets';
    protected $casts = [
        'model_id' => 'json',
        'album' => 'json',
        'description' => 'json',
    ];
}
