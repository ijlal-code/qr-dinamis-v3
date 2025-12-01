<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicQr extends Model
{
    protected $fillable = ['code', 'target_url'];
}
