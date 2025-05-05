<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Promotion extends Model
{
    use HasFactory, QueryScopes, SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
        'method',
        'discouInformation',
        'neverEndDate',
        'startDate',
        'endDate',
        'publish',
        'order',
    ];
    protected $casts = [
        'discountInFomation' => 'json',
    ];
    protected $table = 'promotions';
    public function products() {
        return $this->belongsToMany(Promotion::class, 'promotion_product_variant','product_id', 'promotion_id')
        ->withPivot(
            'product_variant_id',
            'model',
        )->withTimestamps();
    }
}
