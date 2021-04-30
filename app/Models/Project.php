<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'customer_id'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function projectdocs()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

}
