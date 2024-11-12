<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';

        // RAW SQL QUERY
        // $employees = DB::select('
        //     select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id
        // ');

        // Query builder
        // $employees = DB::table('employees')
        // ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        // ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        // ->get();

        // Eloquent Model
        $employees = Employee::all();

        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $pageTitle = 'Create Employee';
        // RAW SQL Query
        // $positions = DB::select('select * from positions');

        // Eloquent Model
        $positions = Position::all();

        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // INSERT QUERY
        // DB::table('employees')->insert([
        //     'firstname' => $request->firstName,
        //     'lastname' => $request->lastName,
        //     'email' => $request->email,
        //     'age' => $request->age,
        //     'position_id' => $request->position,
        // ]);

        // Eloquent model
        $employee = new Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $pageTitle = 'Employee Detail';

        // RAW SQL QUERY
        // $employee = collect(DB::select('
        //     select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id
        //     where employees.id = ?
        // ', [$id]))->first();

        // Query builder SQL
        // $employee = DB::table('employees')
        // ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        // ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        // ->where('employees.id', '=', $id)
        // ->first();

        // SQL MODEL

        $employee = Employee::find($id);

        return view('employee.show', compact('pageTitle', 'employee'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //Edit
        $pageTitle = 'Employee Edit';

        // $employee = DB::find($id)
        // ->join('positions', 'positions.id', '=', 'employees.position_id')
        // ->select('employees.*', 'positions.name as position_name')
        // ->first();
        //     DB::table('employees')->select('employees.*', 'positions.name as position_name', 'positions.id as position_id')
        //     ->leftJoin('positions', 'positions.id', '=', 'employees.position_id')
        //     ->where('employees.id', $id)
        //     ->first();

            // $employees = DB::table('employees')->select('employees.*', 'positions.name as position_name', 'positions.id as position_id')
            //     ->leftJoin('positions', 'positions.id', '=', 'employees.position_id')
            //     ->where('employees.id', $id)
            //     ->first();

        // RAW SQL QUERY
        // $employees = collect(DB::select('
        //     select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id
        //     where employees.id = ?
        // ', [$id]))->first();

        // QUERY BUILDER SQL
        // $employees = DB::table('employees')->select('employees.*', 'positions.name as position_name', 'positions.id as position_id')
        //     ->leftJoin('positions', 'positions.id', '=', 'employees.position_id')
        //     ->where('employees.id', $id)
        //     ->first();
        $positions = Position::all();
        $employees = Employee::find($id);
        // $positions = DB::table('positions')->get();
        // dump($positions);
        // dump($employees);
        // $query =
        // dump($employees);
        return view('employee.edit', compact('pageTitle', 'employees', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();


        // dump(positions);
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // QUERY BUILDER
        // DB::table('employees')
        // ->where('id', $id)
        // ->delete();
        $employee = Employee::find($id)->delete();

        // $employee->delete();
        // if($employee){
        //     Alert('Employee deleted successfully');

        // }
        return redirect()->route('employees.index');
    }
}
