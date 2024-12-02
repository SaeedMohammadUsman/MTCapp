<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // public function index()
    // {
    //     $departments = Department::all();
    //     return view('departments.index', compact('departments'));
    // }

    public function index(Request $request)
    {
        // Search by ID or Title
        $departments = Department::when($request->input('search'), function ($query, $search) {
            $query->where('id', $search)
                ->orWhere('title_en', 'like', "%$search%")
                ->orWhere('title_fa', 'like', "%$search%");
        })->paginate(7); // Paginate results (7 per page)

        return view('departments.index', compact('departments'))->with('info', 'Your changes will be applied after saving.');;
    }




    //



    public function create()
    {
        return view('departments.create');
    }
    public function store(Request $request)
    {
         //user form request, it is cleaner and separates validation logic from controller methods, making the code more maintainable
        // below link for more information https://chatgpt.com/share/673c25d7-bb34-8002-9890-eaa661d347af
        $request->validate([
            'title_en' => 'required|string|max:55',
            'title_fa' => 'required|string|max:55',
            'status' => 'required|in:active,inactive,archived',
        ]);

        Department::create($request->all());

        // return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        
        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        
    //     return redirect()->route('departments.index')->with('success', 'Department created successfully!')
    // ->with('debug', dd(session()->all()));


    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }
    
    public function update(Request $request, Department $department)
    {
       //user form request, itis cleaner and separates validation logic from controller methods, making the code more maintainable.
        
        $request->validate([
            'title_en' => 'required|string|max:55',
            'title_fa' => 'required|string|max:55',
            'status' => 'required|in:active,inactive,archived',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
