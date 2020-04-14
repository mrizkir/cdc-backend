<?php
use Laravel\Lumen\Testing\WithoutMiddleware;

class UsersPasienTest extends TestCase
{
    use WithoutMiddleware;
    
    public function getToken()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        return $content['access_token'];
    }
    public function testPasienIndex()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];
        
        $this->assertTrue(true);   
    }
    public function testPasienStore()
    {
        $response = $this->call('post','/v1/auth/login', ['username'=>'admin','password'=>'1234']);
        $this->seeStatusCode(200);
        $content = json_decode($response->getContent(),true);
        $token=$content['access_token'];

        
        $this->assertTrue(true);       
    }
}