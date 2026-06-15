<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Project;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function get_api_keys(string $project_id, string $client_id)
    {
        try {
            $db_proj = Project::where("project_id", $project_id)->first();
            $db_client = Client::where("client_id", $client_id)->first();

            // Doesnt exist.
            if ($db_proj == null || $db_client == null) {
                return response("Project or client doesnt exist!", 401);
            }

            // DEACTIVATED (default behaviour).
            if ($db_client->is_authorized == false) {
                error_log("client not authorized!");
                return response("Unauthorized access!", 401);
            }

            error_log("project_id: " . $db_proj->id);
            $attr = Attribute::where("project_id", $db_proj->id)->get();

            error_log("client authorized successfully!");
            error_log("attr: " . $attr);
            // ACTIVATED (allowed).
            return $attr;
        } catch (Exception $err) {
            error_log("error msg: " . $err->getMessage());
            return response("Couldnot fetch api keys for client!", 500);
        }
    }

    public function check_client_activation_status(
        string $project_id,
        string $client_id,
    ) {
        try {
            $proj = Project::where("project_id", $project_id)->get();
            if (is_null($proj)) {
                return response("Project not found!", 401);
            }

            error_log("hola");

            $client = Client::where("client_id", $client_id)->first();
            error_log($client);
            if (is_null($client)) {
                return response()->json(["is_client_created" => false], 200);
            }

            return response()->json(
                [
                    "is_client_created" => true,
                ],
                201,
            );
        } catch (Exception $err) {
            error_log($err->getMessage());
            return response(
                "Error while creating new client: " . $err->getMessage(),
                500,
            );
        }
    }

    // Needs int project_id for foreign key of Client!
    public function create_new_client(Request $request)
    {
        try {
            $req = $request->all();

            error_log($req["projectId"]);

            $db_project = Project::where(
                "project_id",
                $req["projectId"],
            )->first();
            if (is_null($db_project)) {
                return response("Project doesnt exist!", 401);
            }

            $client = new Client();
            $client->project_id = $db_project->id;
            $client->client_id = $req["clientId"];
            $client->boot_time = $req["bootTime"];
            $client->coordinates = $req["coordinates"];
            $client->location = $req["location"];
            $client->save();

            return response("Created new client!", 200);
        } catch (Exception $err) {
            error_log("CreateNewClient error: " . $err->getMessage());
            return response("Error creating new client!", 500);
        }
    }

    /**
     * Fetch the specified Client.
     */
    public function get_client_by_id(string $client_id)
    {
        return Client::where("client_id", $client_id)->get();
    }

    /**
     * Fetch the specified Project's Clients.
     */
    public function show(string $project_id)
    {
        return Project::find($project_id)->clients;
    }

    /**
     * Activate client for api access.
     */
    public function activate_client(int $client_id)
    {
        error_log("activating client..." . $client_id);

        try {
            $db_client = Client::find($client_id);
            $db_client->is_authorized = true;
            $db_client->save();
        } catch (Exception $err) {
            error_log($err->getMessage());
        }

        return response("Client activation success!", 200);
    }

    public function deactivate_client(int $client_id)
    {
        $db_client = Client::find($client_id);
        $db_client->is_authorized = false;
        $db_client->save();

        return response("Client deactivation success!", 200);
    }
}
