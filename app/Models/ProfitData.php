<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitData extends Model
{
    use HasFactory;
    protected $table = 'tbl_profitdata';
    protected $primaryKey = 'id';
}
