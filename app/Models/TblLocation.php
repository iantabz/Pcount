<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblLocation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_location';
    public $timestamps = false;

    public function app_users()
    {
        return $this->hasOne(TblAppUser::class, 'location_id', 'location_id');
    }

    public function app_audit()
    {
        return $this->hasOne(TblAppAudit::class, 'location_id', 'location_id');
    }
}
