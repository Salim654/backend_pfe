<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    // Define the table name if different from the model name (optional)
    protected $table = 'factures';

    public $timestamps = false;
    protected $fillable = [
        'reference',
        'date',
        'due_date',
        'type',
        'discount',
        'client_id',
        'taxe_id'
    ];

    // Define relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function taxe()
    {
        return $this->belongsTo(Taxe::class, 'taxe_id');
    }
    public function produits()
    {
        return $this->hasMany(Factureprod::class, 'factures_id');
    }
}
