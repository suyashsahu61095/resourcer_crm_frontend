<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id'
    ];

    public function project(){
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function productdocs()
    {
        return $this->hasMany(ProductFile::class);
    }

    public function category() {
        return $this->belongsTo(ProductCategory::class, 'category', 'id');
    }
}
