<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblAppUser extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_app_user';
    public $timestamps = false;
}
