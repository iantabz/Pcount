<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUnposted extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_unposted';
}
