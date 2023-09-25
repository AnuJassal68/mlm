<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketing extends Model
{
    use HasFactory;

    protected $table = 'tbl_ticketing';
    protected $primaryKey = 'ticketId';
    public $timestamps = false;
 

    protected $fillable = [
        'ticketId',
        'userId',
        'subject',
        'message',
        'type',
        'insertedAt',
        'isSolved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
