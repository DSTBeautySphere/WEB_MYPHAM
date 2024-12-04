<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_prediction extends Model
{
    use HasFactory;
    protected $table = 'products_prediction';
    
    protected $fillable = ['Product_Name', 'Rating','Product_Size','Skin_Type','Price_USD','is_purchased'];
    public $timestamps = false;

}
