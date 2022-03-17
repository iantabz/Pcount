<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblNavCountdata extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_nav_countdata';

    public function AppCount()
    {
        return $this->hasMany(TblAppCountdata::class, 'itemcode', 'itemcode');
    }

    // public function (Type $var = null)
    // {
    //     # code...
    // }
}
