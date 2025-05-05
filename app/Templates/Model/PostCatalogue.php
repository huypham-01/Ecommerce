<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class {class}Catalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;
    
    protected $table = '{module}_catalogues';
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
        return $this->belongsToMany(Language::class, '{module}_catalogue_language', '{module}_catalogue_id', 'language_id')
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
    public function {module}s() {
        return $this->belongsToMany({class}::class, '{module}_catalogue_{module}', '{module}_id', '{module}_catalogue_id');
    }
    public function {module}_catalogue_language() {
        return $this->hasMany({class}CatalogueLanguage::class, '{module}_catalogue_id', 'id');
    }
    public static function isNodeCheck($id = 0) {
        ${module}Catalogue = {class}Catalogue::find($id);
        if(${module}Catalogue->rgt - ${module}Catalogue->lft !== 1) {
            return false;
        }
        return true;
    }
}
