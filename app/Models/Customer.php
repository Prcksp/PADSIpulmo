<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_customer', 'alamat_customer', 'no_telepon_customer', 'tanggal_lahir_customer', 'email_customer', 'jumlah_poin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'jumlah_poin',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir_customer' => 'date',
        'jumlah_poin' => 'integer',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_customer';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Mutator for setting the date
     */
    public function setTanggalLahirCustomerAttribute($value)
    {
        // Format the date to Y-m-d before saving
        $this->attributes['tanggal_lahir_customer'] = date('Y-m-d', strtotime($value));
    }
}
