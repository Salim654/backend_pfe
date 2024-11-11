<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
    public $timestamps = false;
    protected $fillable = [
        'organization_id',
        'name',
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
