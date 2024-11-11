<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categorys';
    public $timestamps = false;
    protected $fillable = [
        'organization_id',
        'category',
        'reference',
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
