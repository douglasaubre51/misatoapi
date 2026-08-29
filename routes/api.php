<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Projects
Route::get("/projects/all", [ProjectController::class, "index"]);

Route::get("/projects/{id}", [ProjectController::class, "show"]);

Route::get("/projects/{id}/project/with_attributes", [
    ProjectController::class,
    "show_project_details_with_attributes",
]);

Route::post("/projects", [ProjectController::class, "store"]);

Route::put("/projects/{id}", [ProjectController::class, "update"]);

Route::post("/projects/new", [ProjectController::class, "create"]);

Route::delete("/projects/attribute/{attribute_id}/remove_attribute", [
    ProjectController::class,
    "remove_attribute_by_id",
]);

Route::get("/projects/{project_id}/all_attributes", [
    ProjectController::class,
    "get_all_attributes",
]);

Route::post("/projects/{project_id}/project/add_attribute", [
    ProjectController::class,
    "add_attribute",
]);

Route::post("/projects/project/attribute", [
    ProjectController::class,
    "add_attribute_by_project",
]);

Route::put("/projects/{project_id}/project/attribute", [
    ProjectController::class,
    "update_attribute",
]);

Route::delete("/projects/{project_id}", [ProjectController::class, "delete"]);

// Clients
Route::get("/clients/{project_id}", [ClientController::class, "show"]);

Route::get("/clients/{client_id}/activate", [
    ClientController::class,
    "activate_client",
]);
Route::get("/clients/{client_id}/deactivate", [
    ClientController::class,
    "deactivate_client",
]);

Route::get("/clients/{project_id}/{client_id}/check_activation_status", [
    ClientController::class,
    "check_client_activation_status",
]);

Route::get("/clients/{client_id}", [
    ClientController::class,
    "get_client_by_id",
]);

Route::get("/clients/{project_id}/{client_id}/fetch_env_keys", [
    ClientController::class,
    "get_api_keys",
]);

Route::post("/clients/new_client", [
    ClientController::class,
    "create_new_client",
]);
