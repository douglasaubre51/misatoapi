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
        try {
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

            $db_project = Project::where("project_id", $uuid)->first();

            for ($i = 0; $i < $arr_count; $i++) {
                if ($keys[$i] == "") {
                    break;
                }

                $new_attribute = new Attribute();
                $new_attribute->project_id = $db_project->id;
                $new_attribute->key = $keys[$i];
                $new_attribute->value = $values[$i];

                $new_attribute->save();
            }

            return response("Project creation success!", 200);
        } catch (Exception $err) {
            error_log("Create project error: " . $err->getMessage());
            return response("Create project error: " . $err->getMessage(), 500);
        }
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

    public function show_project_details_with_attributes(int $id)
    {
        try {
            $db_proj = Project::with("attributes")->find($id);
            if (is_null($db_proj)) {
                return response("Project doesnt exist!", 400);
            }

            return response($db_proj);
        } catch (Exception $ex) {
            error_log("show_project_details error: " . $ex->getMessage());
            return response("Error fetching project details!", 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $updated_proj = $request->all();

        $db_proj = Project::find($id);
        $db_proj->title = $updated_proj["title"];

        return "updated project: " . $id;
    }

    public function add_attribute_by_project(Request $request)
    {
        try {
            $res = $request->all();

            $db_proj = Project::find($res["projectId"]);
            if (is_null($db_proj)) {
                return response("Project doesnt exist!", 400);
            }

            $attr = new Attribute();
            $attr->project_id = $db_proj->id;
            $attr->key = $res["key"];
            $attr->value = $res["value"];
            $attr->save();
        } catch (Exception $err) {
            error_log("Add attribute to project error: " . $err->getMessage());
            return response("Add attribute to project error!", 500);
        }
    }
    public function update_attribute(Request $request)
    {
        try {
            $res = $request->all();

            $db_attr = Attribute::find($res["id"]);
            if (is_null($db_attr)) {
                return response("attribute doesnt exist!", 400);
            }

            $db_attr->key = $res["key"];
            $db_attr->value = $res["value"];
            $db_attr->save();
        } catch (Exception $err) {
            error_log("Update attribute error: " . $err->getMessage());
            return response("Update attribute error!", 500);
        }
    }

    public function delete(int $project_id)
    {
        try {
            $db_proj = Project::where("id", $project_id)->first();
            if (is_null($db_proj)) {
                return response("Project doesnt exist!", 401);
            }

            $db_proj->delete();

            return response("Project deleted successfully", 200);
        } catch (Exception $err) {
            error_log("Project deletion error: " . $err->getMessage());
            return response("Project deletion error", 500);
        }
    }
}
