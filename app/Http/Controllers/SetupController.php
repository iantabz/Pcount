<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\Employee;
use App\Models\TblAppAudit;
use App\Models\TblAppUser;
use App\Models\TblItemCategoryMasterfile;
use App\Models\TblLocation;
use App\Models\TblUser;
use App\Models\TblUsertype;
use App\Models\TblVendorMasterfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function getResultsLocation()
    {
        // dd(request()->all());
        $company = request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;

        return TblLocation::with(['app_users', 'app_audit'])
            ->where([
                ['company', 'LIKE', "%$company%"],
                ['business_unit', 'LIKE', "$bu"],
                ['department', 'LIKE', "$dept"],
                ['section', 'LIKE', "$section"]
            ])
            ->paginate(10);
        // $query =  TblLocation::groupBy(['company', 'business_unit', 'section']);
        // dd($query->get());
        // return $query->paginate(10);
    }

    public function toggleStatusLocation()
    {
        // dd(request()->all());
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
        // dd(request()->all());
        $last_name = request()->lastname;
        return Employee::selectRaw('emp_id, emp_no, emp_pins as emp_pin, name, position')->where([['name', 'LIKE', "%$last_name%"], ['current_status', 'Active']])->get();
        // dd($result);
    }


    public function createLocation(CreateLocationRequest $request)
    {
        $validated = $request->validated();
        // dd(request()->section);
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
            DB::transaction(function () use ($validated, $section, $dept) {
                // // dd($validated);

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
            });
            return response()->json(['message' => 'User created successfully!'], 200);
        }

        // dd($validated);
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
}
