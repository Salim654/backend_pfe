<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxe extends Model
{
    use HasFactory;
    protected $table = 'taxes';
    public $timestamps = false;
    protected $fillable = [
        'wording',
        'short_name',
        'value',
        'value_type',
        'application',
        'organization_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


}
