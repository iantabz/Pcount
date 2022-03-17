<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUser extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'users';

    public function getCompany()
    {
        return $this->hasOne(company::class, 'company_code', 'company_code')
            ->SelectRaw('
                company.company_code,
                company.company,
                business_unit.bcode,
                business_unit.business_unit,
                department.dept_code,
                department.dept_name,
                section.section_code,
                section.section_name
        ')
            ->join('business_unit', 'business_unit.company_code', '=', 'company.company_code')
            ->join('department', 'department.bunit_code', '=', 'business_unit.bunit_code')
            ->join('section', 'section.dept_code', '=', 'department.dept_code');
        //     return $this->hasOne(company::class, 'company_code', 'company_code')
        //         ->SelectRaw('
        //         company_code,
        //         company
        // ');
    }
    public function getBu()
    {
        return $this->hasOne(BusinessUnit::class, 'bunit_code', 'bunit_code')->selectRaw('bcode, business_unit, bunit_code')
            ->where('bunit_code', 'business_unit.bunit_code');
    }
    public function getDept()
    {
        return $this->hasOne(department::class, 'dept_code', 'dept_code')->selectRaw('dcode, dept_code, dept_name');
    }
    public function getSection()
    {
        return $this->hasOne(section::class, 'section_code', 'section_code')->selectRaw('section_code, section_name');
    }
}
