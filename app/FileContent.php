<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class FileContent extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'name','email','phone','age','salary','phone_type','feedback'
    ];




}
