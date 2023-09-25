<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $table = 'tbl_income';
    protected $primaryKey = 'id';
    protected $fillable = ['incometype', 'income', 'createdate', 'incomelog','userid','byuserid','propertyid']; // Add all the columns here
}
