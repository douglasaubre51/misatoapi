<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Projects
Route::get("/projects/all", [ProjectController::class, "index"]);

Route::get("/projects/{id}", [ProjectController::class, "show"]);

Route::post("/projects", [ProjectController::class, "store"]);

Route::put("/projects/{id}", [ProjectController::class, "update"]);

Route::post("/projects/new", [ProjectController::class, "create"]);

Route::get("/projects/{attribute_id}/remove_attribute", [
    ProjectController::class,
    "remove_attribute_by_id",
]);

Route::get("/projects/{project_id}/all_attributes", [
    ProjectController::class,
    "get_all_attributes",
]);

Route::post("/projects/add_attribute", [
    ProjectController::class,
    "add_attribute",
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
