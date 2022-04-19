<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Material;
use App\Models\MaterialUpload;
use App\Models\MasterOption;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Auth;
use Validator;

use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.master.material.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(), 
            [
                'kode'   => 'required',
                'deskripsi'      => 'required',
                'plant'      => 'required',
                'mtype_id'      => 'required',
                'mgroup_id'      => 'required',
                'bunit_id'      => 'required',
            ],
            [
                'kode.required'  => 'Kode harus diisi.',
                'deskripsi.required'     => 'Deskripsi harus diisi.',
                'plant.required'     => 'Plant harus diisi.',
                'mtype_id.required'     => 'Material Type harus diisi.',
                'mgroup_id.required'     => 'Matl Group harus diisi.',
                'bunit_id.required'     => 'Base Unit harus diisi.',
            ]);

            if($validation->fails())
            {
                return response()->json(array(
                    'status' => 0,
                    'msg'   => $validation->errors()->all()
                ));
            }
            else
            {
                $req = $request->all();

                if(empty($req['id']))
                {
                    $req['updated_by']   = Auth::user()->id;
                    $req['created_by']   = Auth::user()->id;
                    
                    Material::create($req);
                    
                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil disimpan'
                    ));
                }
                else
                {
                    $req['updated_by']   = Auth::user()->id;        
                    $req['updated_at']   = Carbon::now();

                    Material::find($req['id'])->fill($req)->save();
                    
                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil diubah'
                    ));
                    
                }
            }
        }
        catch (QueryException $er)
        {
            return response()->json(array(
                'status' => 0,
                'msg'   => 'Data gagal disimpan'
            ));
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpload(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $validation = Validator::make($request->all(), 
            [
                'formUpload'   => 'required',
            ],
            [
                'formUpload.required'  => 'File harus diisi.',
            ]);

            if($validation->fails())
            {
                return response()->json(['status' => 0,
                'msg'   => $validation->errors()->all()],200);
            }
            else
            {
                $req = $request->all();
                
                $fileVar = $req['formUpload'];

                $fileName = $fileVar->getClientOriginalName();
                                
                $sheetData = [];
                
                if($fileVar->getClientMimeType() == 'text/csv')
                {
                    $fileStorage = fopen($fileVar->getRealPath(),'r');
                    while(! feof($fileStorage))
                    {
                        $csv = fgetcsv($fileStorage, 1024, "\t");
                        $sheetData[] = $csv;
                    }
                }
                else
                {
                    $spreadsheet = IOFactory::load($fileVar->getRealPath());

                    $sheetData = $spreadsheet->getActiveSheet()->toArray();
                }
                
                $x = 0;   
                $tData = 0;
                
                $matUpload = MaterialUpload::create([
                    'nama_file' => $fileName,
                    'jumlah_data' => $tData,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);

                $arrKey = null;
                
                foreach($sheetData as $sD)
                {
                    if(empty($sD[0]))
                    {
                        break;
                    }
                    if($x == 0)
                    {
                        foreach($sD as $k => $v)
                        {
                            if(empty($v))
                            {
                                break;
                            }
                            $arrKey[$v] = $k;
                        }
                        $arrKey = (object) $arrKey;
                        
                        $x++;
                        continue;
                    }
                    
                    $id = null;

                    if(isset($req['id']))
                    {
                        if($req['id'])
                        {
                            $id = $req['id'];
                        }
                    }

                    if(!$sD[$arrKey->material]) continue;

                    $tData++;

                    // $mrp    = MasterOption::where('kode', $sD[$arrKey->mrp])->where('tipe', 'MRPGROUP')->first();
                    $mtyp   = MasterOption::where('kode', $sD[$arrKey->mtyp])->where('tipe', 'MATTYPE')->first();
                    $matl   = MasterOption::where('kode', $sD[$arrKey->matl])->where('tipe', 'MATGROUP')->first();
                    $bun    = MasterOption::where('kode', $sD[$arrKey->bun])->where('tipe', 'BUNIT')->first();
                    // $valcl  = MasterOption::where('kode', $sD[$arrKey->valcl])->where('tipe', 'VALCL')->first();
                                        
                    $arrData = [
                        'kode' => $sD[$arrKey->material],
                        'deskripsi' => $sD[$arrKey->deskripsi],
                        'upload_id' => $matUpload->id,
                        'plant' => 1300,
                        'updated_by' => Auth::user()->id
                    ];

                    // if($mrp) $arrData['mrp_id'] = $mrp->id; else continue;
                    if($mtyp) $arrData['mtype_id'] = $mtyp->id; else continue;
                    if($matl) $arrData['mgroup_id'] = $matl->id; else continue;
                    if($bun) $arrData['bunit_id'] = $bun->id; else continue;
                    // if($valcl) $arrData['valcl_id'] = $valcl->id; else continue;

                    $prev = Material::where('kode', $sD[$arrKey->material])->first();

                    if($prev)
                    {
                        Material::find($prev->id)->fill($arrData)->save();
                    }
                    else
                    {
                        $arrData['created_by'] = Auth::user()->id;
                        Material::create($arrData);
                    }
                }

                MaterialUpload::find($matUpload->id)->fill(['jumlah_data' => $tData])->save();
                
                DB::commit();
                return response()->json(['status' => 1,
                'msg'   => 'Data berhasil disimpan'],200);
            }
        }   
        catch(Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => 0, 'msg' => $e->getMessage()],200);
        }     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterOption  $masterOption
     * @return \Illuminate\Http\Response
     */
    public function show(MasterOption $masterOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterOption  $masterOption
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterOption $masterOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterOption  $masterOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterOption $masterOption)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download($ids)
    {
       try 
        {
            $downloadFile = MaterialUpload::find($ids);

            if(!$downloadFile)
                return response()->json(array(
                    "status" => 0,
                    "msg"   => "Data gagal didownload."
                ));

            $strDownload = "";
            $arrFile = [];
            foreach($downloadFile->material()->get() as $valMat)
            {
                $mtype = json_decode($valMat->mtype->warna);

                $arr = [
                    $valMat->kode,
                    'T',
                    $valMat->mtype->kode,
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    'X',
                    $valMat->deskripsi,
                    $valMat->plant,
                    $valMat->mtype->nilai,
                    '1000',
                    '00',
                    $valMat->bunit->kode,
                    $valMat->mgroup->kode,
                    '',
                    '02',
                    '001',
                    '',
                    'PD',
                    '231',
                    '1310',
                    'EX',
                    '0',
                    '0',
                    'KP',
                    '000',
                    '',
                    '',
                    'E',
                    '',
                    '',
                    '',
                    'M',
                    'S',
                    '0',
                    '0',
                    '1',
                    $mtype->val,
                    $valMat->plant,
                    'MWST',
                    '1',
                    'MWSS',
                    '1',
                    '',
                    '1',
                    'NORM',
                    'NORM',
                    '01',
                    '2KM',
                    '2ZZ',
                    '2ZZ',
                    '',
                    '',
                    '',
                    '',
                    $mtype->cost,
                    '',
                    '',
                    ''

                ];
                $arrFile[] = implode("\t",$arr);
            }

            $fileName = 'SAP_'.Carbon::now()->format('dmYHis').'.txt';
            $content = implode("\r\n",$arrFile);

            $headers = [
                'Content-type' => 'text/plain', 
                'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
                'Content-Length' => 0
            ];
            
              return response()->make($content, 200, $headers);
        } 
        catch (QueryException $ex) 
        {
            return response()->json(array(
               "status" => 0,
                "msg"   => "Data gagal didownload."
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterOption  $masterOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $req = $request->all();
        try 
        {
            Material::find($req['sId'])->delete();
            
            return response()->json(array(
               "status" => 1,
                "msg"   => "Data berhasil dihapus."
            ));
        } 
        catch (QueryException $ex) 
        {
            return response()->json(array(
               "status" => 0,
                "msg"   => "Data gagal dihapus."
            ));
        }
    }
    
    public function dt(Request $request)
    {
        $req    = $request->all();
        
        $datas   = Material::with('createdby', 'updatedby', 'mrp', 'mtype', 'mgroup', 'bunit', 'valcl');  
        
        if(!empty($req['search']))
        {
            $datas->where(function($q) use($req)
            {
                $q->where('nama', 'like',str_replace('*','%',$req['search']));
                $q->orWhere('deskripsi', 'like', str_replace('*','%',$req['search']));
            });
        }

        if(!empty($req['mrp']))
        {
            $datas->where('mrp_id', $req['mrp']);
        }

        if(!empty($req['mtype']))
        {
            $datas->where('mtype_id', $req['mtype']);
        }

        if(!empty($req['mgroup']))
        {
            $datas->where('mgroup_id', $req['mgroup']);
        }

        if(!empty($req['bunit']))
        {
            $datas->where('bunit_id', $req['bunit']);
        }

        if(!empty($req['valcl']))
        {
            $datas->where('valcl_id', $req['valcl']);
        }

        $datas->orderBy('id','asc');
        
        return  Datatables::of($datas)
                ->editColumn('id', '{{$id}}')
                ->make(true);
    }
    
    public function dtUpload(Request $request)
    {
        $req    = $request->all();
        
        $datas   = MaterialUpload::with('createdby', 'updatedby');  
        
        if(!empty($req['search']))
        {
            $datas->where('nama_file', 'like',str_replace('*','%',$req['search']));
        }

        $datas->orderBy('id','asc');
        
        return  Datatables::of($datas)
                ->editColumn('id', '{{$id}}')
                ->addColumn('action',function($datas){
                    $str = '';

                    $str .= '<a class="btn btn-success btn-sm" target="_blank" href="'.route('materialdownload', $datas->id).'"><i class="fa fa-download"></i></a>';

                    return $str;
                })
                ->make(true);
    }
    
    public function select(Request $request)
    {
        $tags = null;
        
        $term = trim($request->input('q'));
        $tags = Material::where('kode','like', '%'.$term.'%')->limit(50)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 
                                 'text' => $tag->kode, 
                                 'deskripsi' => $tag->deskripsi, 
                                 'mrp' => ['id' => $tag->mrp->id, 'kode' => $tag->mrp->kode, 'deskripsi' => $tag->mrp->deskripsi]];
        }

        return response()->json(array('items' => $formatted_tags), 200);
    }

}
