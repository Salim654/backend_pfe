<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factureprod extends Model
{
    use HasFactory;


    protected $table = 'factureprods';
    public $timestamps = false;
    protected $fillable = [
        'quantity',
        'discount',
        'product_id',
        'factures_id',
        'taxe_id',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class, 'factures_id');
    }
    

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function taxe()
    {
        return $this->belongsTo(Taxe::class, 'taxe_id');
    }
}
