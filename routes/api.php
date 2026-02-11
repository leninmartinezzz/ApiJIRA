<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grupo de rutas para JIRA
Route::prefix('jira')->group(function () {

    // 🔧 DIAGNÓSTICO Y CONEXIÓN
    Route::get('/test', [App\Http\Controllers\JiraController::class, 'testConnection']);

    // 📁 PROYECTOS
    Route::get('/projects', [App\Http\Controllers\JiraController::class, 'getProjects']);

    // 📄 ISSUES (TICKETS)
    Route::get('/issues', [App\Http\Controllers\JiraController::class, 'getAllIssues']);
    Route::get('/issuesIdentify', [App\Http\Controllers\JiraController::class, 'getIssuesHelpYT']);
    Route::get('/issue/{issueKey}', [App\Http\Controllers\JiraController::class, 'showIssue']);

    Route::post('/issue/{issueKey}/assign', [App\Http\Controllers\JiraController::class, 'assignIssue']);

    // 🔍 BÚSQUEDAS ESPECÍFICAS
    Route::get('/issues/project/{projectKey}', [App\Http\Controllers\JiraController::class, 'getIssuesByProject']);
    Route::get('/issues/status/{status}', [App\Http\Controllers\JiraController::class, 'getIssuesByStatus']);
    Route::get('/issues/my', [App\Http\Controllers\JiraController::class, 'getMyIssues']);
});
