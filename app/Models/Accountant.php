<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'adresse',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'accountant_organization');
    }
}
