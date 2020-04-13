<?php

$router->get('/', function () use ($router) {
    $res=[
            'success'=>true,
            'message'=>'BINTAN-CDC (Covid-19 Data Center) API Micro-Service',
            'payload'=>[]
        ];

    return response()->json($res);
});
$router->group(['prefix'=>'v1'], function () use ($router)
{
    $router->post('/dashboard/front',['uses'=>'DashboardController@frontindex','as'=>'dashboard.frontindex']);
    $router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);    
});

$router->group(['prefix'=>'v1','middleware'=>'auth:api'], function () use ($router)
{   
    $router->post('/dashboard/admin',['uses'=>'DashboardController@adminindex','as'=>'dashboard.adminindex']);

    //authentication    
    $router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
    $router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
    $router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

    //setting - permissions
    $router->get('/setting/permissions',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\PermissionsController@index','as'=>'permissions.index']);
    $router->post('/setting/permissions/store',['middleware'=>['role:superadmin'],'uses'=>'Setting\PermissionsController@store','as'=>'permissions.store']);    
    $router->delete('/setting/permissions/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\PermissionsController@destroy','as'=>'permissions.destroy']);
    
    //setting - roles
    $router->get('/setting/roles',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@index','as'=>'roles.index']);
    $router->post('/setting/roles/store',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@store','as'=>'roles.store']);
    $router->post('/setting/roles/storerolepermissions',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@storerolepermissions','as'=>'roles.storerolepermissions']);
    $router->put('/setting/roles/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@update','as'=>'roles.update']);
    $router->delete('/setting/roles/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@destroy','as'=>'roles.destroy']);    
    $router->get('/setting/roles/{id}/permission',['middleware'=>['role:superadmin'],'uses'=>'Setting\RolesController@rolepermissions','as'=>'roles.permission']);    
    
    //setting - users
    $router->get('/setting/users',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@index','as'=>'users.index']);
    $router->post('/setting/users/store',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@store','as'=>'users.store']);
    $router->post('/setting/users/storeuserpermissions',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@storeuserpermissions','as'=>'users.storeuserpermissions']);
    $router->post('/setting/users/revokeuserpermissions',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@revokeuserpermissions','as'=>'users.revokeuserpermissions']);
    $router->put('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@update','as'=>'users.update']);
    $router->delete('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@destroy','as'=>'users.destroy']);    
    $router->get('/setting/users/{id}/permission',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@userpermissions','as'=>'users.permission']);    
    $router->get('/setting/users/{id}/petugas',['middleware'=>['role:superadmin|gugustugas|petugas|pptk'],'uses'=>'Setting\UsersController@userpetugas','as'=>'users.petugas']);    
    
    //setting - users gugustugas
    $router->get('/setting/usersgugustugas',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@index','as'=>'usersgugustugas.index']);
    $router->post('/setting/usersgugustugas/store',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@store','as'=>'usersgugustugas.store']);
    $router->put('/setting/usersgugustugas/{id}',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@update','as'=>'usersgugustugas.update']);
    $router->put('/setting/usersgugustugas/{id}',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@update','as'=>'usersgugustugas.update']);
    $router->delete('/setting/usersgugustugas/{id}',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@destroy','as'=>'usersgugustugas.destroy']);    
    
    //setting - users petugas
    $router->get('/setting/userspetugas',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@index','as'=>'userspetugas.index']);
    $router->post('/setting/userspetugas/store',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@store','as'=>'userspetugas.store']);
    $router->put('/setting/userspetugas/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@update','as'=>'userspetugas.update']);
    $router->put('/setting/userspetugas/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@update','as'=>'userspetugas.update']);
    $router->delete('/setting/userspetugas/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@destroy','as'=>'userspetugas.destroy']);    
  
});
