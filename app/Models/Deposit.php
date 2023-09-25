<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'tbl_deposit';
    protected $guarded = ['id'];
}
