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
    public function delete_clients(Project $project)
    {
        error_log("client: " . $project->id);

        $project->clients->each(function ($client) {
            error_log("client: " . $client->id);
            $client->delete();
        });
    }
}
