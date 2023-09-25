<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    const CREATED_AT = 'createdate'; 
    protected $table = 'tbl_avtivity_log';
}
