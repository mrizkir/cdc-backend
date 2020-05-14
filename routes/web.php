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

    $router->post('/pasien/lokasiterakhirpublik',['uses'=>'Setting\UsersPasienController@lokasiterakhirpublik','as'=>'pasien.lokasiterakhirpublik']);    

    //data master - kecamatan
    $router->get('/dmaster/kecamatan',['uses'=>'DMaster\KecamatanController@index','as'=>'kecamatan.index']);        
    $router->get('/dmaster/kecamatan/{id}/desa',['uses'=>'DMaster\KecamatanController@desakecamatan','as'=>'kecamatan.desakecamatan']); 
    
    //data master - desa
    $router->get('/dmaster/desa',['uses'=>'DMaster\DesaController@index','as'=>'desa.index']);        

    //status pasien -data master
    $router->get('/dmaster/statuspasien',['uses'=>'DMaster\StatusPasienController@index','as'=>'statuspasien.index']);                  
});

$router->group(['prefix'=>'v1','middleware'=>'auth:api'], function () use ($router)
{   
    $router->post('/dashboard/admin',['uses'=>'DashboardController@adminindex','as'=>'dashboard.adminindex']);  

    //authentication    
    $router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
    $router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
    $router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

    //dmaster - fasilitas karantina
    $router->get('/dmaster/fasilitaskarantina',['middleware'=>['role:superadmin|petugas|pasien'],'uses'=>'DMaster\FasilitasKarantinaController@index','as'=>'fasilitaskarantina.index']);
    $router->post('/dmaster/fasilitaskarantina/store',['middleware'=>['role:superadmin'],'uses'=>'DMaster\FasilitasKarantinaController@store','as'=>'fasilitaskarantina.store']);
    $router->put('/dmaster/fasilitaskarantina/{id}',['middleware'=>['role:superadmin|fasilitas|pasien'],'uses'=>'DMaster\FasilitasKarantinaController@update','as'=>'fasilitaskarantina.update']);
    $router->delete('/dmaster/fasilitaskarantina/{id}',['middleware'=>['role:superadmin'],'uses'=>'DMaster\FasilitasKarantinaController@destroy','as'=>'fasilitaskarantina.destroy']);    

    //dmaster - Kecamatan    
    $router->post('/dmaster/kecamatan/store',['middleware'=>['role:superadmin'],'uses'=>'DMaster\KecamatanController@store','as'=>'kecamatan.store']);
    $router->put('/dmaster/kecamatan/{id}',['middleware'=>['role:superadmin'],'uses'=>'DMaster\KecamatanController@update','as'=>'kecamatan.update']);
    $router->delete('/dmaster/kecamatan/{id}',['middleware'=>['role:superadmin'],'uses'=>'DMaster\KecamatanController@destroy','as'=>'kecamatan.destroy']);

    //dmaster - Desa    
    $router->post('/dmaster/desa/store',['middleware'=>['role:superadmin'],'uses'=>'DMaster\DesaController@store','as'=>'desa.store']);
    $router->put('/dmaster/desa/{id}',['middleware'=>['role:superadmin'],'uses'=>'DMaster\DesaController@update','as'=>'desa.update']);
    $router->delete('/dmaster/desa/{id}',['middleware'=>['role:superadmin'],'uses'=>'DMaster\DesaController@destroy','as'=>'desa.destroy']);    
    
    //digunakan untuk mendapatkan lokasi terakhir seluruh pasien -lokasi
    $router->post('/pasien/lokasiterakhir',['middleware'=>['role:superadmin|gugustugas|pasien'],'uses'=>'Setting\UsersPasienController@lokasiterakhir','as'=>'pasien.lokasiterakhir']);    

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
    $router->post('/setting/users/uploadfoto/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@uploadfoto','as'=>'users.uploadfoto']);
    $router->post('/setting/users/storeuserpermissions',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@storeuserpermissions','as'=>'users.storeuserpermissions']);
    $router->post('/setting/users/revokeuserpermissions',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@revokeuserpermissions','as'=>'users.revokeuserpermissions']);
    $router->put('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@update','as'=>'users.update']);
    $router->delete('/setting/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'Setting\UsersController@destroy','as'=>'users.destroy']);    
    $router->get('/setting/users/{id}/permission',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@userpermissions','as'=>'users.permission']);    
    $router->get('/setting/users/{id}/petugas',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersController@userpetugas','as'=>'users.petugas']);    
    
    //setting - users gugustugas
    $router->get('/setting/usersgugustugas',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@index','as'=>'usersgugustugas.index']);
    $router->post('/setting/usersgugustugas/store',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@store','as'=>'usersgugustugas.store']);
    $router->put('/setting/usersgugustugas/{id}',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@update','as'=>'usersgugustugas.update']);
    $router->delete('/setting/usersgugustugas/{id}',['middleware'=>['role:superadmin|gugustugas'],'uses'=>'Setting\UsersGugusTugasController@destroy','as'=>'usersgugustugas.destroy']);    
    
    //setting - users petugas
    $router->get('/setting/userspetugas',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@index','as'=>'userspetugas.index']);
    $router->post('/setting/userspetugas/store',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@store','as'=>'userspetugas.store']);
    $router->put('/setting/userspetugas/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@update','as'=>'userspetugas.update']);
    $router->delete('/setting/userspetugas/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPetugasController@destroy','as'=>'userspetugas.destroy']);    

    //setting - users pasien

    //digunakan untuk mendapatkan seluruh pasien
    $router->get('/setting/userspasien',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@index','as'=>'userspasien.index']);    
    //digunakan untuk menyimpan pasien baru
    $router->post('/setting/userspasien/store',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@store','as'=>'userspasien.store']);
    //digunakan untuk mendapatkan detail user sekaligus riwayat sakit yang disimpan di array [history] dan 5 posisi terakhir disimpan di array [lokasi]
    $router->get('/setting/userspasien/{id}',['middleware'=>['role:superadmin|gugustugas|petugas|pasien'],'uses'=>'Setting\UsersPasienController@show','as'=>'userspasien.show']);
    //digunakan mengupdate data diri pasien
    $router->put('/setting/userspasien/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@update','as'=>'userspasien.update']);
    //digunakan mengupdate status pasien, daftar kode status lihat di file app\Helpers\Helper
    $router->put('/setting/userspasien/updatestatus/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@updatestatus','as'=>'userspasien.updatestatus']);
    //digunakan menghapus pasien
    $router->delete('/setting/userspasien/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@destroy','as'=>'userspasien.destroy']);    
    //digunakan menambah lokasi
    $router->post('/setting/userspasien/tambahlokasi/{id}',['middleware'=>['role:superadmin|gugustugas|petugas|pasien'],'uses'=>'Setting\UsersPasienController@tambahlokasi','as'=>'userspasien.tambahlokasi']);    
    //digunakan untuk menyimpan detail pasien
    $router->post('/setting/userspasien/storedetail/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@storedetail','as'=>'userspasien.storedetail']);    
    //digunakan untuk mengubah detail pasien
    $router->post('/setting/userspasien/updatedetail/{id}',['middleware'=>['role:superadmin|gugustugas|petugas'],'uses'=>'Setting\UsersPasienController@updatedetail','as'=>'userspasien.updatedetail']);    

    
});
