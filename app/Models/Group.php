<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens; 

class Group extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nama_group',
        'password_group',
        'nama_leader',
        'email_leader',
        'nomor_wa_leader',
        'id_line_leader',
        'github_leader',
        'tmp_lahir_leader',
        'tgl_lahir_leader',
        'is_binusian',
        'cv',
        'flazz_card',
        'id_card',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

}
