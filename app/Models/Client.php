<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        "client_id",
        "project_id",
        "boot_time",
        "location",
        "coordinates",
        "is_authorized",
    ];
}
