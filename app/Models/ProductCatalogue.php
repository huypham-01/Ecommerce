<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class ProductCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;
    
    protected $table = 'product_catalogues';
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
    ];
  
    public function languages() {
        return $this->belongsToMany(Language::class, 'product_catalogue_language', 'product_catalogue_id', 'language_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content',
        )->withTimestamps();
    }
    public function products() {
        return $this->belongsToMany(Post::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id');
    }
    public function product_catalogue_language() {
        return $this->hasMany(ProductCatalogueLanguage::class, 'product_catalogue_id', 'id');
    }
    public static function isNodeCheck($id = 0) {
        $productCatalogue = ProductCatalogue::find($id);
        if($productCatalogue->rgt - $productCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
