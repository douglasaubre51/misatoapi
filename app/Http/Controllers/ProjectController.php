<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Project;
use Exception;

class ProjectController extends Controller
{
    public function index()
    {
        $db_projects = Project::get();
        return $db_projects;
    }

    public function create(Request $request)
    {
        $req = $request->all();

        $proj = new Project();
        $uuid = (string) Str::uuid();
        $proj->project_id = $uuid;
        $proj->title = $req["title"];

        $keys = $req["keys"];
        $arr_count = count($keys);
        $values = $req["values"];

        $did_save = $proj->save();
        if ($did_save != true) {
            return response("Project creation failed!", 401);
        }

        $db_project = Project::where("project_id", $uuid)->get();

        for ($i = 0; $i < $arr_count; $i++) {
            $new_attribute = new Attribute();
            $new_attribute->project_id = $db_project->id;
            $new_attribute->key = $keys[$i];
            $new_attribute->value = $values[$i];

            $new_attribute->save();
        }

        return response("Project creation success!", 200);
    }

    // int project_id (primary key) of Project!
    public function add_attribute(Request $request)
    {
        try {
            $req = $request->all();

            $attr = new Attribute();
            $attr->project_id = $req["project_id"];
            $attr->key = $req["key"];
            $attr->value = $req["value"];

            $attr->save();
            return response("Created new attribute!", 200);
        } catch (Exception $ex) {
            return response(
                "Error creating new attribute: " . $ex->getMessage(),
                500,
            );
        }
    }

    public function remove_attribute_by_id(int $attribute_id)
    {
        $attr = Attribute::destroy($attribute_id);
    }

    // int project_id (primary key) of Project!
    public function get_all_attributes(int $project_id)
    {
        return Project::find($project_id)->attributes;
    }

    public function show(int $id)
    {
        $proj = Project::find($id);

        return $proj;
    }

    public function update(Request $request, string $id)
    {
        $updated_proj = $request->all();

        $db_proj = Project::find($id);
        $db_proj->title = $updated_proj["title"];

        return "updated project: " . $id;
    }
}
