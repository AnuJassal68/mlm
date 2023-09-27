<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Blockavel\LaraBlockIo\LaraBlockIoFacade;
class LaraBlockIo extends Model
{
    use HasFactory;
    public static  function test()
    {
        // dd('dfhjkl');
        return LaraBlockIo::getBalanceInfo();
    }
}
