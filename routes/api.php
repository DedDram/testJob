<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api_key'])->prefix('v1')->group(function () {
    Route::get('/buildings/{building_id}/organizations', [OrganizationController::class, 'getOrganizationsByBuilding']);
    Route::get('/activities/{activity_id}/organizations', [OrganizationController::class, 'getOrganizationsByActivity']);
    Route::get('/organizations/nearby', [OrganizationController::class, 'getNearbyOrganizations']);
    Route::get('/organizations/by-activity-name', [OrganizationController::class, 'getOrganizationsByActivityName']);
    Route::get('/organizations/by-name', [OrganizationController::class, 'getOrganizationsByName']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'getOrganizationById']);
});
