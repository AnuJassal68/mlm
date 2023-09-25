<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpentExtra extends Model
{
    use HasFactory;
    protected $table = 'tbl_spent_extra';
    protected $primaryKey = 'id';
}
