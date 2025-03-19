<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;


    protected $fillable = [

        'user_id',
        'bank_id',
        'tid',
        'ip',
        'port',
        'ssl',
        'compKey1',
        'compKey2',
        'logoUrl',
        'serialNumber',
        'merchantName',
        'pin',
        'accountBalance',
        'mid',
        'merchantaddress'

    ];


    protected $casts = [
        'accountBalance' => 'string',
    ];

    protected $table = 'terminals'; // Assuming your table name is 'terminals'
    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
