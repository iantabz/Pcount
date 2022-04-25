<?php

namespace App\Http\Controllers;

use App\Exports\LocationData;
use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\BusinessUnit;
use App\Models\company;
use App\Models\department;
use App\Models\Employee;
use App\Models\section;
use App\Models\TblAppAudit;
use App\Models\TblAppUser;
use App\Models\TblItemCategoryMasterfile;
use App\Models\TblLocation;
use App\Models\TblLocationRack;
use App\Models\TblNavCount;
use App\Models\TblUser;
use App\Models\TblUsertype;
use App\Models\TblVendorMasterfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SetupController extends Controller
{
    public function getResultsLocation()
    {
        $company = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateString();
        $type = request()->type;
        $countType = request()->countType;



        if ($type == 'LocationSetup') return TblLocation::with(['app_users', 'app_audit', 'nav_count'])
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
            ->where([
                ['company', 'LIKE', "%$company%"],
                ['business_unit', 'LIKE', "$bu"],
                ['department', 'LIKE', "$dept"],
                ['section', 'LIKE', "$section"],
                ['done', 'LIKE', "false"],
                ['type', $countType]
            ])
            ->whereDate('batchDate', '=', $date)
            ->paginate(10);

        if ($type == 'LocationMonitoring') {
            $racks = [];

            $query = TblLocation::join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                ->whereDate('batchDate', '=', $date)
                ->groupBy(['company', 'business_unit', 'department', 'section'])
                ->get();

            // dd($query);


            // dd($query[0]['company']);
            $query->map(function ($row) use (&$countType, &$date, &$racks, &$query) {
                $comp = $query[0]['company'];
                $bu = $query[0]['business_unit'];
                $dept = $query[0]['department'];
                $section = $query[0]['section'];

                $row->racks = TblLocation::join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
                    ->where([
                        ['company', 'LIKE', "%$comp%"],
                        ['business_unit', 'LIKE', "%$bu%"],
                        ['department', 'LIKE', "%$dept%"],
                        ['section', 'LIKE', "%$section%"]
                    ])
                    ->whereDate('batchDate', '=', $date)
                    ->groupBy('rack_desc')
                    ->get()
                    ->toArray();
                // dd($row->racks);

                $racks = $row->racks;
                // dd($racks);
            });

            $data['data'] = $query;
            // $data['racks'] = $racks;
            // dd($data);
            return $data;
        }

        // $row->racks = TblLocation::where('')
    }

    public function toggleStatusLocation()
    {
        $result = TblLocation::where('location_id', request()->id)->update(['status' => request()->status]);
        if ($result)  return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function getResultsUser()
    {
        return TblUser::paginate(6);
    }

    public function toggleStatusUser()
    {
        $result = TblUser::where('id', request()->id)->update(['status' => request()->status]);
        if ($result)  return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function createUser(CreateUserRequest $request)
    {
        $validated = $request->validated();
        // dd($validated, $validated['name']['emp_id']);

        if (!request()->id) {
            $comp_code = $validated['name']['company_code'];
            $bu_code = $validated['name']['bunit_code'];
            $dept_code = $validated['name']['dept_code'];
            $section_code = $validated['name']['section_code'];
            $position = $validated['name']['position'];

            $getCompany = company::where([['company_code', $comp_code], ['status', 'active']])->get()->toarray();
            $getBU = BusinessUnit::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['status', 'active']
            ])->get()->toArray();
            $getDept = department::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['dept_code', $dept_code],
                ['status', 'active']
            ])->get()->toArray();
            $getSection = section::where([
                ['company_code', $comp_code],
                ['bunit_code', $bu_code],
                ['dept_code', $dept_code],
                ['section_code', $section_code],
                ['status', 'active']
            ])->get()->toArray();


            $company = $getCompany[0]['acroname'];
            $business_unit = $getBU[0]['business_unit'];
            $department = $getDept[0]['dept_name'];
            $section = $getSection[0]['section_name'];

            // dd($company, $business_unit, $department, $section, $position);
            $checkUsername = User::where('username', $validated['username'])->exists();
            if (!$checkUsername) {
                User::create([
                    'name' => $validated['name']['name'],
                    'username' => $validated['username'],
                    'password' => bcrypt($validated['password']),
                    'company' => $company,
                    'business_unit' => $business_unit,
                    'department' => $department,
                    'section' => $section,
                    'usertype_id' => $validated['usertype_id'],
                    'position' => $position
                ]);
                return response()->json(['message' => 'User created successfully!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        }
        // dd($validated);
        $result = User::where('id', request()->id)->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'usertype_id' => $validated['usertype_id']
        ]);
        if ($result) return response()->json(['message' => 'Update successful!'], 201);
    }

    public function searchEmployee()
    {
        $last_name = request()->lastname;
        return Employee::selectRaw('emp_id, emp_no, emp_pins as emp_pin, name, position, company_code, bunit_code, dept_code, section_code')
            ->where([
                ['name', 'LIKE', "%$last_name%"],
                ['current_status', 'Active']
            ])->get();
    }

    public function createLocation(CreateLocationRequest $request)
    {
        // dd(request()->all());
        $batchDate = Carbon::parse(base64_decode(request()->countDate))->startOfDay()->toDateString();
        $validated = $request->validated();
        $countType = request()->countType;

        if (!request()->forPrintVendor) {
            $vendor = 'null';
        } else {
            $vendor = request()->forPrintVendor;
        }
        if (!request()->forPrintCategory) {
            $category = 'null';
        } else {
            $category = request()->forPrintCategory;
        }

        if (!request()->section) {
            $section = 'null';
        } else {
            $section = request()->section;
        }
        if (!request()->department) {
            $dept = 'null';
        } else {
            $dept = request()->department;
        }

        if ($validated['selectedEmp']['emp_id'] == $validated['selectedAudit']['emp_id'])   return response()->json(['message' => 'Same names are now allowed'], 406);

        if (!request()->location_id) {
            // dd($validated['selectedEmp']['emp_id']);
            $comp = $validated['company'];
            $bu = $validated['business_unit'];
            $app_user = $validated['selectedEmp']['emp_id'];
            $app_audit = $validated['selectedAudit']['emp_id'];

            $ifAppUserExists = TblNavCount::join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_location', 'tbl_location.location_id', '=', 'tbl_nav_count.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', $app_user],
                    ['tbl_app_user.done', false],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', $app_user],
                    ['tbl_app_audit.done', false],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->exists();

            $ifAppAuditExists = TblNavCount::join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_nav_count.location_id')
                ->join('tbl_location', 'tbl_location.location_id', '=', 'tbl_nav_count.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', $app_audit],
                    ['tbl_app_audit.done', false],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', $app_audit],
                    ['tbl_app_user.done', false],
                    ['tbl_nav_count.type', $countType]
                ])->whereDate('batchDate', $batchDate)
                ->exists();
            // dd($ifAppUserExists, $ifAppAuditExists);

            if (!$ifAppUserExists && !$ifAppAuditExists) {
                DB::transaction(function () use ($validated, $section, $dept, $vendor, $category, $batchDate, $countType) {
                    $location = TblLocation::create([
                        'company' => $validated['company'],
                        'business_unit' => $validated['business_unit'],
                        // 'department' => $validated['department'],
                        'department' => $dept,
                        'section' => $section,
                        'rack_desc' => $validated['rack_desc'],
                        'date_added' => now(),
                        'status' => 'true'
                    ]);

                    TblAppUser::create([
                        'emp_id' => $validated['selectedEmp']['emp_id'],
                        'emp_no' => $validated['selectedEmp']['emp_no'],
                        'emp_pin' => $validated['selectedEmp']['emp_pin'],
                        'name' => $validated['selectedEmp']['name'],
                        'position' => $validated['selectedEmp']['position'],
                        'location_id' => $location->id,
                        'date_register' => now(),
                        'done' => 'false',
                        'locked' => 'false'
                    ]);

                    TblAppAudit::create([
                        'emp_id' => $validated['selectedAudit']['emp_id'],
                        'emp_no' => $validated['selectedAudit']['emp_no'],
                        'emp_pin' => $validated['selectedAudit']['emp_pin'],
                        'name' => $validated['selectedAudit']['name'],
                        'position' => $validated['selectedAudit']['position'],
                        'location_id' => $location->id,
                        'date_register' => now()
                    ]);

                    TblNavCount::create([
                        'byCategory' =>  $category === 'null' ? 'False' : 'True',
                        'categoryName' => $category,
                        'byVendor' => $vendor === 'null' ? 'False' : 'True',
                        'vendorName' => $vendor,
                        'location_id' => $location->id,
                        'type' => $countType,
                        'batchDate' => $batchDate
                    ]);
                });

                return response()->json(['message' => 'User created successfully!'], 200);
            } else if ($ifAppUserExists) {
                return response()->json(['message' => 'Inventory Clerk already exists!'], 406);
            } else if ($ifAppAuditExists) {
                return response()->json(['message' => 'IAD Audit already exists!'], 406);
            }
        }

        // dd(request()->all());
        DB::transaction(function () use ($validated, $section, $dept) {
            TblLocation::where('location_id', request()->location_id)->update([
                'company' => $validated['company'],
                'business_unit' => $validated['business_unit'],
                // 'department' => $validated['department'],
                'department' => $dept,
                'section' => $section,
                'rack_desc' => $validated['rack_desc'],
            ]);

            TblAppUser::where('location_id', request()->location_id)->update([
                'emp_id' => $validated['selectedEmp']['emp_id'],
                'emp_no' => $validated['selectedEmp']['emp_no'],
                'emp_pin' => $validated['selectedEmp']['emp_pin'],
                'name' => $validated['selectedEmp']['name'],
                'position' => $validated['selectedEmp']['position']
            ]);

            TblAppAudit::where('location_id', request()->location_id)->update([
                'emp_id' => $validated['selectedAudit']['emp_id'],
                'emp_no' => $validated['selectedAudit']['emp_no'],
                'emp_pin' => $validated['selectedAudit']['emp_pin'],
                'name' => $validated['selectedAudit']['name'],
                'position' => $validated['selectedAudit']['position']
            ]);
        });

        return response()->json(['message' => 'Update successful!'], 201);
    }

    public function getUsertypes()
    {
        return TblUsertype::all();
    }

    public function getVendorMasterfile()
    {
        $search = request()->vendor;

        if (!$search) {
            return TblVendorMasterfile::paginate(10);
        } else {

            return TblVendorMasterfile::where('vendor_name', 'LIKE', "%$search%")->paginate(10);
        }
    }
    public function getItemCategoryMasterfile()
    {
        $search = request()->category;

        if (!$search) {
            return TblItemCategoryMasterfile::paginate(10);
        } else {

            return TblItemCategoryMasterfile::where('category', 'LIKE', "%$search%")
                ->orWhere('dept_code', $search)
                ->paginate(10);
        }
    }

    public function generateLocation()
    {
        session(['data' => $this->LocationData()]);
        return Excel::download(new LocationData, 'LocationData.xlsx');
    }

    public function LocationData()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $comp = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->countdate))->startOfDay()->toDateString();

        $query =  TblLocation::with(['app_users', 'app_audit', 'nav_count'])
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
            ->where([
                ['company', 'LIKE', "%$comp%"],
                ['business_unit', 'LIKE', "$bu"],
                ['department', 'LIKE', "$dept"],
                ['section', 'LIKE', "$section"],
                ['done', 'LIKE', "false"]

            ])
            ->whereDate('batchDate', '=', $date)
            ->get()
            ->toArray();

        $header = array(
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'countDate' => $date,
            'data' => $query
        );

        return $header;
    }

    public function getRacks()
    {

        return TblLocationRack::where([
            'company' => request()->company,
            'business_unit' => request()->business_unit,
            'department' => request()->department,
            'section' => request()->section
        ])->get();
    }

    public function createRack()
    {
        // dd(request()->all());
        $validate =  TblLocationRack::where([
            'company' => request()->company,
            'business_unit' => request()->business_unit,
            'department' => request()->department,
            'section' => request()->section,
            'rack_name' => request()->name
        ])->exists();

        if (!request()->id) {
            if (!$validate) {
                TblLocationRack::create([
                    'company' => request()->company,
                    'business_unit' => request()->business_unit,
                    'department' => request()->department,
                    'section' => request()->section,
                    'rack_name' => request()->name
                ]);
                return response()->json(['message' => 'User created successfully!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        } else {
            if (!$validate) {
                TblLocationRack::where('id', request()->id)->update(['rack_name' => request()->name]);
                return response()->json(['message' => 'Update successful!'], 200);
            }
            return response()->json(['message' => 'Already exists!'], 406);
        }
    }
}
