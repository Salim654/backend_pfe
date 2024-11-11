<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = 'organizations';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'adresse',
        'country_id',
    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function categorys()
    {
        return $this->hasMany(Category::class);
    }
    public function tvas()
    {
        return $this->hasMany(Tva::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function taxes()
    {
        return $this->hasMany(Taxe::class);
    }
    public function accountants()
    {
        return $this->belongsToMany(Accountant::class, 'accountant_organization');
    }
}
