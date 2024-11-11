<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tva extends Model
{
    use HasFactory;
    protected $table = 'tvas';
    public $timestamps = false;
    protected $fillable = [
        'organization_id',
        'value',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
