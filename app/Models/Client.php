<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'identification',
        'email',
        'organization_id',
        'phone',
        'adresse',
    ];

    /**
     * Get the user that owns the client.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
