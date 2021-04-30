<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'file_name'
    ];

    public function projectCategory(){
        return $this->belongsTo(ProjectCategory::class, 'category_id', 'id');
    }

}
