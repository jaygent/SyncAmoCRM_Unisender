<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'clientId';

    protected $fillable=['clientId','access_token','refresh_token','baseDomain','expires','unisender_api_key'];
}