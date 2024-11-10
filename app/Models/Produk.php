<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Specify the table associated with the model
    protected $table = 'Produk';

    // Specify the primary key
    protected $primaryKey = 'id_produk';

    // Specify whether the IDs are auto-incrementing
    public $incrementing = false;

    // Disable timestamps as your table doesn't have created_at/updated_at
    public $timestamps = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'id_produk', 'nama_produk', 'deskripsi_produk', 'harga_produk'
    ];

    // Optionally, define the data type casts for attributes
    protected $casts = [
        'harga_produk' => 'float',
    ];
}
