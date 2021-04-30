<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'file_name'
    ];

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

}
