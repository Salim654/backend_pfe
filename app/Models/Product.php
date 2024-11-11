<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = [
        'reference',
        'designation',
        'category_id',
        'organization_id',
        'brand_id',
        'price',
        'tva_id',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function tva()
    {
        return $this->belongsTo(Tva::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
