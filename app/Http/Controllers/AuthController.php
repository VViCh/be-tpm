<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    
    public function register(Request $request) : JsonResponse 
    {
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

        $group = Group::create([
            'nama_group' => $request->nama_group,
            'password_group' => Hash::make($request->password_group),
            'nama_leader' => $request->nama_leader,
            'email_leader' => $request->email_leader,
            'nomor_wa_leader' => $request->nomor_wa_leader,
            'id_line_leader' => $request->id_line_leader,
            'github_leader' => $request->github_leader,
            'tmp_lahir_leader' => $request->tmp_lahir_leader,
            'tgl_lahir_leader' => $request->tgl_lahir_leader,
            'is_binusian' => $request->is_binusian,
            'cv'=>$request->cv,
            'flazz_card' => $request->flazz_card,
            'id_card' => $request->id_card,
        ]);


        if ($request->hasFile('cv')) {
            $group->cv = $request->file('cv')->store('cv');
            $group->save();
        }
        if ($request->hasFile('flazz_card') && $request->is_binusian == 'binusian') {
            $group->flazz_card = $request->file('flazz_card')->store('flazz_cards');
            $group->save();
        }
        if ($request->hasFile('id_card') && $request->is_binusian == 'non-binusian') {
            $group->id_card = $request->file('id_card')->store('id_cards');
            $group->save();
        }

        if($group){
            $token = $group->createToken($group->name.'Auth-Token')->plainTextToken;

            return response()->json([
                'message' => 'Registration Successful',
                'token_type' => 'Bearer',
                'token' => $token
            ], 201);
        } else {
            return response()->json([
                'message' => 'Registration Failed',
            ], 500);
        }
    }


    public function login( Request $request ): JsonResponse
    {

        $request->validate([
            'email_leader' => 'required|email',
            'password_group' => 'required|string'
        ]);

        $group = Group::where('email_leader', $request->email_leader)->first();

        if(!$group || !Hash::check($request->password_group, $group->password_group)){
            return response()->json([
                'message' => 'The provided credential are incorrect'
            ], 401);
        }

        $token = $group->createToken($group->name.'Auth-Token')->plainTextToken;

        return response()->json([
            'message' => 'login Successful',
            'token_type' => 'Bearer',
            'token' => $token
        ], 200);

    }

}