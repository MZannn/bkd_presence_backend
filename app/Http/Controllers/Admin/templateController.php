<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Auth;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            return view('pages.admin.employee.insertTemplate');
        }
    }
    public function store(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $data = $request->validate([
                'file' => 'required|file|mimes:xls,xlsx,csv|max:2048',
            ]);
            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('assets/template', 'public');
            }
            Template::create($data);
            return redirect()->route('employee.index')->with('alert', 'File berhasil diupload');
        }
    }
    public function edit($id)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $item = Template::findOrFail($id);
            return view('pages.admin.employee.changeTemplate', compact('item'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $data = $request->validate([
                'file' => 'required|file|mimes:xls,xlsx,csv|max:2048',
            ]);
            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('assets/template', 'public');
            }
            Template::findOrFail($id)->update($data);
            return redirect()->route('employee.index')->with('alert', 'File berhasil diupdate');
        }
    }
}
