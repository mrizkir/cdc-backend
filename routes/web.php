<?php

$router->get('/', function () use ($router) {
    $res=[
            'success'=>true,
            'message'=>'SIMONEV API Micro-Service',
            'payload'=>[]
        ];

    return response()->json($res);
});
$router->group(['prefix'=>'v1'], function () use ($router)
{
    $router->post('/dashboard/front',['uses'=>'DashboardController@frontindex','as'=>'dashboard.frontindex']);
    $router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);
    $router->get('/dmaster/ta/all',['uses'=>'DMaster\TAController@all','as'=>'ta.all']);

});

$router->group(['prefix'=>'v1','middleware'=>'auth:api'], function () use ($router)
{   
    $router->post('/dashboard/admin',['uses'=>'DashboardController@adminindex','as'=>'dashboard.adminindex']);

    //authentication    
    $router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
    $router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
    $router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);


    //data masters - opd
    $router->post('/dmaster/opd',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'DMaster\OrganisasiController@index','as'=>'opd.index']);    
    $router->post('/dmaster/opd/loadopd',['middleware'=>['role:superadmin'],'uses'=>'DMaster\OrganisasiController@loadopd','as'=>'opd.loadopd']);    
    $router->put('/dmaster/opd/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'DMaster\OrganisasiController@update','as'=>'opd.update']);
    $router->get('/dmaster/opd/{id}/unitkerja',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'DMaster\OrganisasiController@opdunitkerja','as'=>'opd.unitkerja']);
    
    //data masters - unit kerja
    $router->post('/dmaster/unitkerja',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'DMaster\SubOrganisasiController@index','as'=>'unitkerja.index']);    
    $router->post('/dmaster/unitkerja/loadunitkerja',['middleware'=>['role:superadmin'],'uses'=>'DMaster\SubOrganisasiController@loadunitkerja','as'=>'unitkerja.loadunitkerja']);    
    $router->put('/dmaster/unitkerja/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'DMaster\SubOrganisasiController@update','as'=>'unitkerja.update']);
    
    //data masters - jenis pelaksanaan
    $router->post('/dmaster/jenispelaksanaan',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'DMaster\JenisPelaksanaanController@index','as'=>'jenispelaksanaan.index']);    
    $router->post('/dmaster/jenispelaksanaan/store',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'DMaster\JenisPelaksanaanController@store','as'=>'jenispelaksanaan.store']);
    $router->put('/dmaster/jenispelaksanaan/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'DMaster\JenisPelaksanaanController@update','as'=>'jenispelaksanaan.update']);
    $router->delete('/dmaster/jenispelaksanaan/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'DMaster\JenisPelaksanaanController@destroy','as'=>'jenispelaksanaan.destroy']);    

    //belanja - rka murni
    $router->post('/belanja/rkamurni',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@index','as'=>'rkamurni.index']);    
    $router->get('/belanja/rkamurni/{id}',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@show','as'=>'rkamurni.show']);    
    $router->put('/belanja/rkamurni/updateuraian/{id}',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@updateuraian','as'=>'rkamurni.updateuraian']);   
    $router->post('/belanja/rkamurni/rencanatarget',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@rencanatarget','as'=>'rkamurni.rencanatarget']);        
    $router->post('/belanja/rkamurni/savetargetfisik',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@savetargetfisik','as'=>'rkamurni.savetargetfisik']);        
    $router->put('/belanja/rkamurni/updatetargetfisik',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@updatetargetfisik','as'=>'rkamurni.updatetargetfisik']);        
    $router->get('/belanja/rkamurni/bulanrealisasi/{id}',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@bulanrealisasi','as'=>'rkamurni.bulanrealisasi']);        
    $router->post('/belanja/rkamurni/savetargetanggarankas',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@savetargetanggarankas','as'=>'rkamurni.savetargetanggarankas']);        
    $router->put('/belanja/rkamurni/updatetargetanggarankas',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@updatetargetanggarankas','as'=>'rkamurni.updatetargetanggarankas']);   
    $router->post('/belanja/rkamurni/realisasi',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@realisasi','as'=>'rkamurni.realisasi']);             
    $router->post('/belanja/rkamurni/saverealisasi',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@saverealisasi','as'=>'rkamurni.saverealisasi']);             
    $router->put('/belanja/rkamurni/updaterealisasi/{id}',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@updaterealisasi','as'=>'rkamurni.updaterealisasi']);             
    $router->delete('/belanja/rkamurni/deleterealisasi/{id}',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Belanja\RKAMurniController@destroy','as'=>'rkamurni.deleterealisasi']);             
    $router->post('/belanja/rkamurni/loaddatakegiatanfirsttime',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Belanja\RKAMurniController@loaddatakegiatanFirsttime','as'=>'rkamurni.loaddatakegiatanfirsttime']);    
    $router->post('/belanja/rkamurni/loaddatauraianfirsttime',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Belanja\RKAMurniController@loaddatauraianFirsttime','as'=>'rkamurni.loaddatauraianfirsttime']);    

    //setting - permissions
    $router->get('/setting/permissions',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\PermissionsController@index','as'=>'permissions.index']);
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
    $router->post('/setting/users/storeuserpermissions',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersController@storeuserpermissions','as'=>'users.storeuserpermissions']);
    $router->post('/setting/users/revokeuserpermissions',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersController@revokeuserpermissions','as'=>'users.revokeuserpermissions']);
    $router->put('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@update','as'=>'users.update']);
    $router->delete('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@destroy','as'=>'users.destroy']);    
    $router->get('/setting/users/{id}/permission',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersController@userpermissions','as'=>'users.permission']);    
    $router->get('/setting/users/{id}/opd',['middleware'=>['role:superadmin|bapelitbang|opd|pptk'],'uses'=>'Setting\UsersController@useropd','as'=>'users.opd']);    
    
    //setting - users bapelitbang
    $router->get('/setting/usersbapelitbang',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'Setting\UsersBapelitbangController@index','as'=>'usersbapelitbang.index']);
    $router->post('/setting/usersbapelitbang/store',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'Setting\UsersBapelitbangController@store','as'=>'usersbapelitbang.store']);
    $router->put('/setting/usersbapelitbang/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'Setting\UsersBapelitbangController@update','as'=>'usersbapelitbang.update']);
    $router->put('/setting/usersbapelitbang/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'Setting\UsersBapelitbangController@update','as'=>'usersbapelitbang.update']);
    $router->delete('/setting/usersbapelitbang/{id}',['middleware'=>['role:superadmin|bapelitbang'],'uses'=>'Setting\UsersBapelitbangController@destroy','as'=>'usersbapelitbang.destroy']);    
    
    //setting - users opd
    $router->get('/setting/usersopd',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersOPDController@index','as'=>'usersopd.index']);
    $router->post('/setting/usersopd/store',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersOPDController@store','as'=>'usersopd.store']);
    $router->put('/setting/usersopd/{id}',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersOPDController@update','as'=>'usersopd.update']);
    $router->put('/setting/usersopd/{id}',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersOPDController@update','as'=>'usersopd.update']);
    $router->delete('/setting/usersopd/{id}',['middleware'=>['role:superadmin|bapelitbang|opd'],'uses'=>'Setting\UsersOPDController@destroy','as'=>'usersopd.destroy']);    
    
    //setting - menus
    $router->get('/setting/menus',['middleware'=>['role:superadmin'],'uses'=>'Setting\MenusController@index','as'=>'menus.index']);
    $router->post('/setting/menus/store',['middleware'=>['role:superadmin'],'uses'=>'Setting\MenusController@store','as'=>'menus.store']);
    $router->put('/setting/menus/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\MenusController@update','as'=>'menus.update']);
    $router->delete('/setting/menus/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\MenusController@destroy','as'=>'menus.destroy']);    
});
