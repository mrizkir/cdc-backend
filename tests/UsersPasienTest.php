<?php
use Laravel\Lumen\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;

class UsersPasienTest extends TestCase
{
    use WithoutMiddleware;
    public function testDatabase()
    {
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');

        $this->assertTrue(true);
    }

    public function testPasienIndex()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];
        
        $response = $this->call('get','/v1/setting/userspasien',['Authorization' => "Bearer $token"]);
        $this->assertEquals(200, $response->status());
                
    }

    public function testPasienStore()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];
        
        $now = \Carbon\Carbon::now()->toDateTimeString(); 

        $response=$this->call('post','/v1/setting/userspasien/store',[
                'username'=> '110022',
                'password'=>Hash::make(1234),            
                'name'=>'Donald Trumph',
                'tempat_lahir'=>'New York',
                'tanggal_lahir'=>'2020-02-02',
                'nomor_hp'=>'18170234346',
                'alamat'=>'St. Donald Reagen',
                'PmKecamatanID'=>'uid',
                'Nm_Kecamatan'=>'Albert',
                'PmDesaID'=>'uid2',
                'Nm_Desa'=>'albert',
                'foto'=>'resources/images/users/no_photo.png',
                'payload'=>'{}',            
                'created_at'=>$now, 
                'updated_at'=>$now
            ],
            ['Authorization' => "Bearer $token"]
        );      
        $content = json_decode($response->getContent(),true);        
        if ($response->status()==500)
        {
            echo $response->getContent();
        }
        else
        {
            echo $content['message'];
        }
        $this->assertEquals(200, $response->status());
        
    } 
    public function testPasienShow()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];
        
        $response=$this->call('get','/v1/setting/userspasien/2',
            ['Authorization' => "Bearer $token"]
        );      
        $content = json_decode($response->getContent(),true);        
        echo $content['message'];
        $this->assertEquals(200, $response->status());
    } 
    public function testPasienUpdate()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];
        
        $now = \Carbon\Carbon::now()->toDateTimeString(); 

        $response=$this->call('put','/v1/setting/userspasien/2',[                
                'username'=> '110022',
                'password'=>Hash::make(1234),            
                'name'=>'Benjamin Nethanyau',
                'tempat_lahir'=>'New York',
                'tanggal_lahir'=>'2020-02-02',
                'nomor_hp'=>'18170234346',
                'alamat'=>'St. Donald Reagen',
                'PmKecamatanID'=>'uid',
                'Nm_Kecamatan'=>'Albert',
                'PmDesaID'=>'uid2',
                'Nm_Desa'=>'albert',
                'foto'=>'resources/images/users/no_photo.png',
                'payload'=>'{}',      
            ],
            ['Authorization' => "Bearer $token"]
        );      
        $content = json_decode($response->getContent(),true);        
        echo $content['message'];
        $this->assertEquals(200, $response->status());
    }    

}