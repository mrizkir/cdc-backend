<?php
use Laravel\Lumen\Testing\WithoutMiddleware;

class RKAMurniTest extends TestCase
{
    use WithoutMiddleware;
    
    public function testRencanaTargetModeTargetFisik ()
    {
        $parameters=['RKARincID'=>'uid590ef2ec7a56','mode'=>'targetfisik'];
        $response = $this->post('/v1/belanja/rkamurni/rencanatarget',$parameters);
        
        $this->seeStatusCode(200);

        $this->seeJsonStructure(['target'=>
                                        [   
                                            'fisik_1'
                                        ]]);

        
    }
    public function testRencanaTargetModeAnggaranKas ()
    {
        $parameters=['RKARincID'=>'uid590ef2ec7a56','mode'=>'targetanggarankas'];
        $response = $this->post('/v1/belanja/rkamurni/rencanatarget',$parameters);
        
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['target'=>
                                        [   
                                            'anggaran_1'
                                        ]]);
        
    }
    public function testRencanaTargetModeBulan ()
    {
        $parameters=['RKARincID'=>'uid590ef2ec7a56','mode'=>'bulan','bulan1'=>1];
        
        $response = $this->post('/v1/belanja/rkamurni/rencanatarget',$parameters);
        
        $this->seeStatusCode(200);
        
        $this->seeJsonStructure(['target'=>
                                        [   
                                            'fisik'
                                        ]]);
        
    }
}