<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ["project_id", "title"];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}
