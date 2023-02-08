<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    protected $table = 'Contacts';
    protected $primaryKey = 'contact_id';
    protected $fillable=['name','email','contact_id','account_id','delete'];

}