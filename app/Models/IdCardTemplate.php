<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use HasFactory;
    protected $table = 'id_card_templates';
    protected $fillable = [
        'id_type',
        'name',
        'file_path',
        'html_file_path',
        'status',
    ];



}
