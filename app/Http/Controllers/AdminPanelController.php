<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class AdminPanelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'nama_group');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $request->validate([
            'sort_by' => 'in:nama_group,created_at',
            'sort_order' => 'in:asc,desc',
        ]);

        $groups = Group::when($search, function ($query, $search) {
            return $query->where('nama_group', 'like', "%{$search}%");
        })
        ->orderBy($sortBy, $sortOrder)
        ->get();

        return response()->json($groups);
    }

    // detail tim
    public function show($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Grup Tidak Ditemukan!'], 404);
        }

        return response()->json($group);
    }

    public function edit(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'nama_group' => 'required|string|max:255',
            'password_group' => 'required|string|min:8|confirmed',
            'nama_leader' => 'required|string|max:255',
            'email_leader' => 'required|string|email|max:255|unique:groups',
            'nomor_wa_leader' => 'required|string|unique:groups',
            'id_line_leader' => 'required|string',
            'github_leader' => 'required|string',
            'tmp_lahir_leader' => 'required|string',
            'tgl_lahir_leader' => 'required|date',
            'is_binusian' => 'required|in:binusian,non-binusian',
            'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'flazz_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048|exclude_if:is_binusian,non-binusian',
            'id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048|exclude_if:is_binusian,binusian',
        ]);

        $group->update([
            'nama_group' => $request->nama_group,
            'nama_leader' => $request->nama_leader,
            'email_leader' => $request->email_leader,
            'nomor_wa_leader' => $request->nomor_wa_leader,
            'id_line_leader' => $request->id_line_leader,
            'github_leader' => $request->github_leader,
            'tmp_lahir_leader' => $request->tmp_lahir_leader,
            'tgl_lahir_leader' => $request->tgl_lahir_leader,
            'is_binusian' => $request->is_binusian,
        ]);

        if ($request->hasFile('cv')) {
    
            if ($group->cv && Storage::exists($group->cv)) {
                Storage::delete($group->cv);
            }

            $group->cv = $request->file('cv')->store('cv');
        }

        if ($request->hasFile('flazz_card') && $request->is_binusian == 'binusian') {
            
            if ($group->flazz_card && Storage::exists($group->flazz_card)) {
                Storage::delete($group->flazz_card);
            }

            $group->flazz_card = $request->file('flazz_card')->store('flazz_cards');
        }

        if ($request->hasFile('id_card') && $request->is_binusian == 'non-binusian') {
    
            if ($group->id_card && Storage::exists($group->id_card)) {
                Storage::delete($group->id_card);
            }
           
            $group->id_card = $request->file('id_card')->store('id_cards');
        }

        $group->save();

        return response()->json(['message' => 'Team updated successfully', 'group' => $group], 200);
    }

    public function destroy($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if ($group->cv && Storage::exists($group->cv)) {
            Storage::delete($group->cv); 
        }

        if ($group->flazz_card && Storage::exists($group->flazz_card)) {
            Storage::delete($group->flazz_card);  
        }

        if ($group->id_card && Storage::exists($group->id_card)) {
            Storage::delete($group->id_card); 
        }

        $group->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

}
