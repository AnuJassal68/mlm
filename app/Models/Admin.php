<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
class Admin extends Authenticatable implements AuthenticatableContract
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'tbl_admin';
}
