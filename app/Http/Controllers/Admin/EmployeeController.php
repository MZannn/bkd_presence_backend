<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Template;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $offices = Office::all();
            $template = Template::all();
            if ($request->has('search')) {
                $items = Employee::with('office')->where('nip', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.employee.index', compact('items', 'offices', 'template'));
            } else if ($request->office_id == null) {
                $data = Employee::with('office');
                if ($data->first() != null) {
                    $items = Employee::with('office')->where('office_id', $data->first()->office->id)->paginate(10);
                    return view('pages.admin.employee.index', compact('items', 'offices', 'template'));
                }
            } else {
                $items = Employee::with('office')->where('office_id', $request->office_id)->paginate(10);
                return view('pages.admin.employee.index', compact('items', 'offices', 'template'));
            }
            $items = Employee::with('office')->paginate(10);
            return view('pages.admin.employee.index', compact('items', 'offices', 'template'));
        }
        if (Auth::user() && Auth::user()->roles == 'ADMIN') {
            $template = Template::all();
            if ($request->has('search')) {
                $items = Employee::with('office')->where('nip', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.employee.index', compact('items', 'template'));
            }
            $items = Employee::with('office')->where('office_id', Auth::user()->office_id)->paginate(10);
            return view('pages.admin.employee.index', compact('items', 'template'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Office::all();
        return view('pages.admin.employee.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:30',
            'password' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'profile_photo_path' => 'image|max:2048',
            'office_id' => 'required|numeric',
        ]);
        if ($request->hasFile('profile_photo_path')) {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store(
                'assets/employee',
                'public'
            );
        }
        $data['password'] = Hash::make($data['password']);
        Employee::create($data);
        return redirect()->route('employee.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::with('office')->get()->firstWhere('nip', $id);
        $items = Office::all();
        return view('pages.admin.employee.edit', compact('employee', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:30',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'profile_photo_path' => 'image|max:2048',
            'office_id' => 'required|numeric',
        ]);
        if ($request->hasFile('profile_photo_path')) {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store(
                'assets/employee',
                'public'
            );
        }
        // dd($data);
        DB::table('employees')->where('nip', $id)->update($data);
        return redirect()->route('employee.index')->with('alert', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Employee::findOrfail($id)->delete();
        return redirect()->route('employee.index')->with('alert', 'Data berhasil dihapus');
    }

    public function toImport()
    {
        return view('pages.admin.employee.import');
    }
    public function import(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new EmployeeImport, $file);
        return redirect()->route('employee.index')->with('alert', 'Data berhasil diimport');
    }

    public function insertTemplate()
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            return view('pages.admin.employee.insertTemplate');
        }
    }
    public function storeTemplate(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $data = $request->validate([
                'file' => 'required|file|mimes:xls,xlsx,csv|max:2048',
            ]);
            if ($request->hasFile('file')) {
                $ext = $request->file('file')->getClientOriginalExtension();
                $data['file'] = $request->file('file')->storeAs('assets/template', "Template File Import Pegawai." . $ext, 'public');
            }
            Template::create($data);
            return redirect()->route('employee.index')->with('alert', 'File berhasil diupload');
        }
    }
    public function editTemplate(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $item = Template::all()->first();
            return view('pages.admin.employee.changeTemplate', compact('item'));
        }
    }

    public function updateTemplate(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $data = $request->validate([
                'file' => 'required|file|mimes:xls,xlsx,csv|max:2048',
            ]);
            if ($request->hasFile('file')) {
                $ext = $request->file('file')->getClientOriginalExtension();
                $fileName = "Template File Import Pegawai." . $ext;
                if (Storage::disk('public')->exists('assets/template/' . $fileName)) {
                    Storage::disk('public')->delete('assets/template/' . $fileName);
                }
                $data['file'] = $request->file('file')->storeAs('assets/template', $fileName, 'public');
            }
            Template::findOrFail($request->id)->update($data);
            return redirect()->route('employee.index')->with('alert', 'File berhasil diupdate');
        }
    }
}