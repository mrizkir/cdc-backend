<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class DesaController extends Controller {
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('desa','orderby')) 
        {            
           $this->putControllerStateSession('desa','orderby',['column_name'=>'Kd_Desa','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('desa.orderby','column_name'); 
        $direction=$this->getControllerStateSession('desa.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('desa','search')) 
        {
            $search=$this->getControllerStateSession('desa','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Desa' :
                    $data = DesaModel::join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','tmPmDesa.PmKecamatanID')
                                        ->select(\DB::raw('"tmPmDesa"."PmDesaID","tmPmDesa"."PmKecamatanID","tmPmKecamatan"."Nm_Kecamatan","tmPmDesa"."Kd_Desa","tmPmDesa"."Nm_Desa","tmPmDesa"."Descr","tmPmDesa"."TA","tmPmDesa"."created_at","tmPmDesa"."updated_at"'))
                                        ->where('tmPmDesa.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where(['Kd_Desa'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_Desa' :
                    $data = DesaModel::join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','tmPmDesa.PmKecamatanID')
                                        ->select(\DB::raw('"tmPmDesa"."PmDesaID","tmPmDesa"."PmKecamatanID","tmPmKecamatan"."Nm_Kecamatan","tmPmDesa"."Kd_Desa","tmPmDesa"."Nm_Desa","tmPmDesa"."Descr","tmPmDesa"."TA","tmPmDesa"."created_at","tmPmDesa"."updated_at"'))
                                        ->where('tmPmDesa.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where('tmPmDesa.Nm_Desa', 'ILIKE', '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = DesaModel::join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','tmPmDesa.PmKecamatanID')
                                ->select(\DB::raw('"tmPmDesa"."PmDesaID","tmPmDesa"."PmKecamatanID","tmPmKecamatan"."Nm_Kecamatan","tmPmDesa"."Kd_Desa","tmPmDesa"."Nm_Desa","tmPmDesa"."Descr","tmPmDesa"."TA","tmPmDesa"."created_at","tmPmDesa"."updated_at"'))
                                ->where('tmPmDesa.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('desa.index'));
        return $data;
    }
    /**
     * digunakan untuk mengganti jumlah record per halaman
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changenumberrecordperpage (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('desa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.desa.datatable")->with(['page_active'=>'desa',
                                                                                'search'=>$this->getControllerStateSession('desa','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                                                'data'=>$data])->render();      
        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderby (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $orderby = $request->input('orderby') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {
            case 'col-Kd_Desa' :
                $column_name = 'Kd_Desa';
            break;  
            case 'col-Nm_Desa' :
                $column_name = 'Nm_Desa';
            break;          
            default :
                $column_name = 'Kd_Desa';
        }
        $this->putControllerStateSession('desa','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('desa'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.desa.datatable")->with(['page_active'=>'desa',
                                                            'search'=>$this->getControllerStateSession('desa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                            'data'=>$data])->render();     

        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * paginate resource in storage called by ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginate ($id) 
    {
        $theme = \Auth::user()->theme;

        $this->setCurrentPageInsideSession('desa',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.desa.datatable")->with(['page_active'=>'desa',
                                                                            'search'=>$this->getControllerStateSession('desa','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                                            'data'=>$data])->render(); 

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * search resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('desa','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('desa','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('desa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.desa.datatable")->with(['page_active'=>'desa',                                                            
                                                                        'search'=>$this->getControllerStateSession('desa','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                        'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                                        'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('desa','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('desa'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('desa',$data->currentPage());

        return view("pages.$theme.dmaster.desa.index")->with(['page_active'=>'desa',
                                                            'search'=>$this->getControllerStateSession('desa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                            'data'=>$data]);               
                }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $kecamatan=KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),false,false);        
        $kecamatan['']='';
        return view("pages.$theme.dmaster.desa.create")->with(['page_active'=>'desa',
                                                                'kecamatan'=>$kecamatan
                                                            ]);  
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $this->validate($request,
            [
                'PmKecamatanID'=>'required|not_in:none', 
                'Kd_Desa' => [
                                new CheckRecordIsExistValidation('tmPmDesa', ['where' => ['PmKecamatanID', '=', $request->input('PmKecamatanID')]]),
                                'required',
                                'min:1',
                                'regex:/^[0-9]+$/'
                ],               
                'Nm_Desa'=>'required|min:5', 
            ],
            [   
                'PmKecamatanID.required'=>'Mohon Kode Desa untuk dipilih',         
                'Kd_Desa.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
                'Kd_Desa.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',

                'Nm_Desa.required'=>'Mohon Nama Desa untuk di isi karena ini diperlukan',
                'Nm_Desa.min'=>'Mohon Nama Desa di isi minimal 5 karakter'
            ]
        );
        
        $desa = DesaModel::create ([
            'PmDesaID'=> uniqid ('uid'),
            'PmKecamatanID'=>$request->input('PmKecamatanID'),
            'Kd_Desa'=>$request->input('Kd_Desa'),        
            'Nm_Desa'=>$request->input('Nm_Desa'),
            'Descr'=>$request->input('Descr'),
            'TA'=>\HelperKegiatan::getTahunPerencanaan(),
        ]);

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('desa.show',['uuid'=>$desa->PmDesaID]))->with('success','Data ini telah berhasil disimpan.');
        }

    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $theme = \Auth::user()->theme;

        $data = DesaModel::with('kecamatan')->findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.desa.show")->with(['page_active'=>'desa',
                                                                    'data'=>$data
                                                                ]);
        }        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = \Auth::user()->theme;
        
        $data = DesaModel::with('kecamatan')->findOrFail($id);
        if (!is_null($data) ) 
        {   
            $kecamatan=KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),false,false);        
            $kecamatan['']='';
            return view("pages.$theme.dmaster.desa.edit")->with(['page_active'=>'desa',
                                                                    'kecamatan'=>$kecamatan,
                                                                    'data'=>$data                                                                    
                                                                ]);
            }        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $desa = DesaModel::with('kecamatan')->find($id);

        $this->validate($request,
        [
            'PmKecamatanID'=>'required|not_in:none',
            'Kd_Desa'=>['required',
                        new IgnoreIfDataIsEqualValidation('tmPmDesa',
                                                            $desa->Kd_Desa,
                                                            ['where'=>['PmKecamatanID', '=', $request->input('PmKecamatanID')]],
                                                            'Kd_Desa'),
                        'min:1',
                        'regex:/^[0-9]+$/'

                    ],   
             
            'Nm_Desa'=>'required|min:5', 
        ],
        [            
            'Kd_Desa.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
            'Kd_Desa.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',
            'Kd_Desa.max'=>'Mohon Kode Urusan untuk di isi maksimal 4 digit',
            
            'Kd_Desa.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',

            'PmKecamatanID.required'=>'Mohon Kode Desa untuk dipilih',

            'Nm_Desa.required'=>'Mohon Nama Desa untuk di isi karena ini diperlukan',
            'Nm_Desa.min'=>'Mohon Nama Desa di isi minimal 5 karakter'
        ]
        );

        $desa->PmKecamatanID = $request->input('PmKecamatanID');
        $desa->Kd_Desa = $request->input('Kd_Desa');
        $desa->Nm_Desa = $request->input('Nm_Desa');
        $desa->Descr = $request->input('Descr');
        
        $desa->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('desa.show',['uuid'=>$desa->PmDesaID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $theme = \Auth::user()->theme;
        
        $desa = DesaModel::find($id);        
        $result=$desa->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('desa'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.desa.datatable")->with(['page_active'=>'desa',
                                                                            'search'=>$this->getControllerStateSession('desa','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('desa.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('desa.orderby','order'),
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('desa.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}