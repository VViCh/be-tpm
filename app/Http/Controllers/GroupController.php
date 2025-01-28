<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; 

class GroupController extends Controller
{
    public function show($id_group)
    {
        $group = Group::find($id_group);

        if(!is_null($group)){
            return response([
                'message' => 'Data group ditemukan',
                'data' => [$group]
            ], 200);
        }

        return response([
            'message' => 'Data group tidak ditemukan',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id_group)
    {
        // Log::info('Group ID: ' . $id_group);
        // Log::info('Group Exists: ' . Group::where('id', $id_group)->exists());
        // Log::info($request->all());
        // Log::info($request->hasFile('cv'));
        $group = Group::find($id_group);
        $request->validate([
            'nama_group' => 'required|string|max:255',
            'is_binusian' => 'required|in:binusian,non-binusian',
            'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'flazz_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048|exclude_if:is_binusian,non-binusian',
            'id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048|exclude_if:is_binusian,binusian',
        ]);

        $group->update([
            'nama_group' => $request->nama_group,
        ]);

        if ($request->hasFile('cv')) {
    
            if ($group->cv && Storage::exists($group->cv)) {
                Storage::delete($group->cv);
            }

            $group->cv = $request->file('cv')->store('cv');
            $group->save();
        }

        if ($request->hasFile('flazz_card') && $request->is_binusian == 'binusian') {
            
            if ($group->flazz_card && Storage::exists($group->flazz_card)) {
                Storage::delete($group->flazz_card);
            }

            $group->flazz_card = $request->file('flazz_card')->store('flazz_cards');
            $group->save();
        }

        if ($request->hasFile('id_card') && $request->is_binusian == 'non-binusian') {
    
            if ($group->id_card && Storage::exists($group->id_card)) {
                Storage::delete($group->id_card);
            }
           
            $group->id_card = $request->file('id_card')->store('id_cards');
            $group->save();
        }

        return response([
            'message' => 'Data group berhasil update',
            'data' => [$group]
        ], 200); 

    }

    public function changePas(Request $request, $id_group){
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        $group = Group::find($id_group);

        if(!is_null($group)){
            if(Hash::check($request->password_lama, $group->password_group)){
                $group->password_group = Hash::make($request->password_baru);
                $group->save;

                return response()->json([
                    'message' => 'Password berhasil diubah',
                    'data' => [$group]
                ],200);
            }else{
                return response()->json([
                    'message' => 'Password lama tidak sesuai',
                    'data' => null
                ],400);
            }
        }

        return response()->json([
            'message' => 'Data group tidak ditemukan',
            'data' => [$group]
        ],400);
    }
}
