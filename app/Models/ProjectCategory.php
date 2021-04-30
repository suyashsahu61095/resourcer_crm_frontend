<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'status'
    ];
}
