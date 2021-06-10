<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //代表允许所有的数据填充，可以运行create和fill方法
    protected $guarded = [];
}
