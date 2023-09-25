<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;
    protected $table = 'tbl_ticket_messgae';
    protected $primaryKey = 'ticketMessageId';

    protected $fillable = [
        'userId',
        'ticketId',
        'message',
        'image',
        'insertedAt',
    ];
}
