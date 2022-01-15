<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    use HasFactory,SoftDeletes;

    protected $table ='pages';
    protected $dates = ['create_at','deleted_at']; // 自動將該欄位轉換成Carbon 物件
    protected $fillable=['page_name','html_class_name','created_at'];
    public static function query_pages()
    {
        $data=Pages::get();
        return $data;
    }
}
