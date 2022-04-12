<?php

namespace App\Http\Controllers;

use App\Exports\LocationData;
use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\Employee;
use App\Models\TblAppAudit;
use App\Models\TblAppUser;
use App\Models\TblItemCategoryMasterfile;
use App\Models\TblLocation;
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
        return TblLocation::with(['app_users', 'app_audit', 'nav_count'])
            ->join('tbl_nav_count', 'tbl_nav_count.location_id', '=', 'tbl_location.location_id')
            ->where([
                ['company', 'LIKE', "%$company%"],
                ['business_unit', 'LIKE', "$bu"],
                ['department', 'LIKE', "$dept"],
                ['section', 'LIKE', "$section"],
                ['done', 'LIKE', "false"]
            ])
            ->whereDate('batchDate', '=', $date)
            ->paginate(10);
        // $query =  TblLocation::groupBy([ 'company', 'business_unit', 'section']);
        // dd($query->get());   
        // return $query->paginate(10);
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
        // dd(request()->all());
        $validated = $request->validated();

        // dd($validated, $validated['name']['name']);
        if (!request()->id) {
            User::create([
                'name' => $validated['name']['name'],
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'company' => $validated['company'],
                'business_unit' => $validated['business_unit'],
                'department' => $validated['department'],
                'section' => $validated['section'],
                'usertype_id' => $validated['usertype_id']
            ]);
            return response()->json(['message' => 'User created successfully!'], 200);
        }
        // dd($validated);
        $result = User::where('id', request()->id)->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'company' => $validated['company'],
            'business_unit' => $validated['business_unit'],
            'department' => $validated['department'],
            'section' => $validated['section'],
            'usertype_id' => $validated['usertype_id']
        ]);
        if ($result) return response()->json(['message' => 'Update successful!'], 201);
    }

    public function searchEmployee()
    {
        $last_name = request()->lastname;
        return Employee::selectRaw('emp_id, emp_no, emp_pins as emp_pin, name, position')
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
        if (!request()->location_id) {
            // dd($validated['selectedEmp']['emp_id']);
            $comp = $validated['company'];
            $bu = $validated['business_unit'];
            $app_user = $validated['selectedEmp']['emp_id'];
            $app_audit = $validated['selectedAudit']['emp_id'];

            $ifAppUserExists = TblLocation::join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_location.location_id')
                ->join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_location.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', 'LIKE', "%$app_user%"],
                    ['tbl_app_user.done', false]
                ])
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', 'LIKE', "%$app_user%"],
                    ['tbl_app_audit.done', false]
                ])
                ->exists();

            $ifAppAuditExists = TblLocation::join('tbl_app_audit', 'tbl_app_audit.location_id', '=', 'tbl_location.location_id')
                ->join('tbl_app_user', 'tbl_app_user.location_id', '=', 'tbl_location.location_id')
                ->where([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_audit.emp_id', 'LIKE', "%$app_audit%"],
                    ['tbl_app_audit.done', false]
                ])
                ->orwhere([
                    ['company', 'LIKE', "%$comp%"],
                    ['business_unit', 'LIKE', "%$bu%"],
                    ['department', 'LIKE', "%$dept%"],
                    ['section', 'LIKE', "$section"],
                    ['tbl_app_user.emp_id', 'LIKE', "%$app_audit%"],
                    ['tbl_app_user.done', false]
                ])
                ->exists();
            // dd($ifAppUserExists, $ifAppAuditExists);

            if (!$ifAppUserExists && !$ifAppAuditExists) {
                DB::transaction(function () use ($validated, $section, $dept, $vendor, $category, $batchDate) {
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
                        // 'type' => $countType,
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
}
