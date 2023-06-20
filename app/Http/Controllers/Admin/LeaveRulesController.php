<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeaveRules;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveRulesController extends Controller
{
      public function index()
      {
            $items = LeaveRules::all();
            return view('pages.admin.leave-rules.index', compact('items'));
      }

      public function create()
      {
            return view('pages.admin.leave-rules.create');
      }

      public function store(Request $request)
      {
            LeaveRules::create([
                  'leave_name' => $request->leave_name,
            ]);
            return redirect()->route('leave-rules.index')->with('alert', 'Data berhasil ditambahkan');
      }

      public function edit(string $id)
      {
            $item = LeaveRules::findOrFail($id);
            return view('pages.admin.leave-rules.edit', compact('item'));
      }

      public function update(Request $request, string $id)
      {
            $item = LeaveRules::findOrFail($id);
            $item->update([
                  'leave_name' => $request->leave_name,
            ]);
            return redirect()->route('leave-rules.index')->with('alert', 'Data berhasil diubah');
      }

      public function destroy(string $id)
      {
            $item = LeaveRules::findOrFail($id);
            $item->delete();
            return redirect()->route('leave-rules.index')->with('alert', 'Data berhasil dihapus');
      }


}