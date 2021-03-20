<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('api/test', 'TestController@index');

$router->post('api/login', 'UsersController@login');

$router->get('api/report/pdf/{id}', 'ReportPDFsController@show');
$router->get('api/test', 'TestController@index');
$router->get('api/maggi', 'TestController@maggi');
$router->get('api/backup', 'BackupController@index');

// EXPORT REPORT
$router->get('api/report/excel/{cashier}/{from}/{to}/{ids}', 'ReportDatasController@exportProcedures');

$router->group(['prefix' => 'api', 'middleware' => ['auth']], function () use ($router) {
    $router->post('/logout', 'UsersController@logout');

    // USER
    $router->get('/user', 'UsersController@getAll');
    $router->get('/user/cashiers', 'UsersController@getAllCashier');
    $router->get('/user/{id}', 'UsersController@getInfo');
    
    // PASSWORD UPDATE
    $router->post('/password', 'UsersController@passwordUpdate');
    
    // PATIENT INFO
    $router->get('/patient', 'PatientsController@getAll');
    $router->get('/patient/{id}', 'PatientsController@getInfo');
    $router->post('/patient', 'PatientsController@create');
    $router->post('/patient/{id}', 'PatientsController@update');
    
    // PATIENT RECORD
    
    // DOCTORS
    $router->get('/doctors', 'PatientRecordsController@getAllDoctors');

    $router->get('/patient/{patientId}/record', 'PatientRecordsController@getAll');
    $router->post('/patient/{patientId}/record', 'PatientRecordsController@create');

    $router->get('/patient/record/{id}/procedure', 'PatientRecordsController@getProcedures');

    // LOOKUP TABLE
    $router->get('/lookup', 'LookupTableController@getAll');
    $router->get('/lookup/search/{type}', 'LookupTableController@getAllByType');
    $router->get('/lookup/{id}', 'LookupTableController@getInfo');
    
    // PROCEDURE SUB CATEOGORY
    $router->get('/procedure/category', 'ProcedureTypeCategoryController@getAll');
    $router->get('/procedure/category/forlisting', 'ProcedureTypeCategoryController@forListing');
    $router->get('/procedure/category/{id}', 'ProcedureTypeCategoryController@getInfo');

    // PROCEDURE
    $router->get('/procedure', 'ProceduresController@getAll');
    $router->get('/procedure/{id}', 'ProceduresController@getInfo');

    
    // ANNOUNCEMENT
    $router->get('/announcement', 'AnnouncementsController@getAll');
    $router->get('/announcement/{id}', 'AnnouncementsController@getInfo');

});

$router->group(['prefix' => 'api', 'middleware' => ['auth', 'supervisor']], function () use ($router) {
    // USER
    $router->post('/user', 'UsersController@create');
    $router->post('/user/{id}', 'UsersController@update');
    $router->post('/user/admin/{id}', 'UsersController@updateRecord'); // for record updating in users list
    $router->post('/user/restore/{id}', 'UsersController@restore');
    $router->delete('/user/{id}', 'UsersController@delete');

    // PATIENT INFO
    $router->post('/patient/restore/{id}', 'PatientsController@restore');
    $router->delete('/patient/{id}', 'PatientsController@delete');
    
    // PATIENT RECORD
    $router->post('/patient/record/restore/{id}', 'PatientRecordsController@restore');
    $router->delete('/patient/record/{id}', 'PatientRecordsController@delete');

    // LOOKUP TABLE
    $router->post('/lookup', 'LookupTableController@create');
    $router->post('/lookup/{id}', 'LookupTableController@update');
    $router->post('/lookup/restore/{id}', 'LookupTableController@restore');
    $router->delete('/lookup/{id}', 'LookupTableController@delete');

    // PROCEDURE SUB CATEGORY
    $router->post('/procedure/category', 'ProcedureTypeCategoryController@create');
    $router->post('/procedure/category/{id}', 'ProcedureTypeCategoryController@update');
    $router->post('/procedure/category/restore/{id}', 'ProcedureTypeCategoryController@restore');
    $router->delete('/procedure/category/{id}', 'ProcedureTypeCategoryController@delete');
    
    // PROCEDURE
    $router->post('/procedure', 'ProceduresController@create');
    $router->post('/procedure/{id}', 'ProceduresController@update');
    $router->post('/procedure/restore/{id}', 'ProceduresController@restore');
    $router->delete('/procedure/{id}', 'ProceduresController@delete');

    // ANNOUNCEMENT
    $router->post('/announcement', 'AnnouncementsController@create');
    $router->post('/announcement/{id}', 'AnnouncementsController@update');
    $router->post('/announcement/restore/{id}', 'AnnouncementsController@restore');
    $router->delete('/announcement/{id}', 'AnnouncementsController@delete');

    // REPORT DATA
    $router->get('/report/data/earnings', 'ReportDatasController@earnings');
    $router->get('/report/data/patients', 'ReportDatasController@patients');
});