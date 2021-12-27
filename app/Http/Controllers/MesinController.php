<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mesin;
use App\Models\MasterOption;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Auth;
use Validator;

use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MesinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.master.mesin.index');
    }     
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        
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
                'merek'      => 'required',
                'proses'      => 'required',
                'spesifikasi'      => 'required',
                'wc_id'      => 'required',
            ],
            [
                'merek.required'     => 'Merek mesin harus diisi.',
                'proses.required'     => 'Proses harus diisi.',
                'spesifikasi.required'     => 'Spesifikasi mesin harus diisi.',
                'wc_id.required'     => 'Work Center harus diisi.',
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

                    $wc    = MasterOption::where('id', $req['wc_id'])->where('tipe', 'WC')->first();
                    $req['proses'] = $wc->deskripsi;
                    
                    Mesin::create($req);
                    
                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil disimpan'
                    ));
                }
                else
                {
                    $req['updated_by']   = Auth::user()->id;        
                    $req['updated_at']   = Carbon::now();

                    $wc    = MasterOption::where('id', $req['wc_id'])->where('tipe', 'WC')->first();
                    $req['proses'] = $wc->deskripsi;

                    Mesin::find($req['id'])->fill($req)->save();
                    
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

                    $wc    = MasterOption::where('kode', $sD[$arrKey->workcenter])->where('tipe', 'WC')->first();
                                        
                    $arrData = [
                        'kode' => $sD[$arrKey->kode],
                        'merek' => $sD[$arrKey->merek],
                        'spesifikasi' => $sD[$arrKey->spesifikasi],
                        'deskripsi' => $sD[$arrKey->deskripsi],
                        'k_min' => $sD[$arrKey->kapasitas_min],
                        'k_max' => $sD[$arrKey->kapasitas_max],
                        'updated_by' => Auth::user()->id
                    ];

                    if($wc)
                    {
                        $arrData['wc_id'] = $wc->id;
                        $arrData['proses'] = $wc->deskripsi;
                    } 
                    else
                    {
                        continue;
                    }

                    $arrData['created_by'] = Auth::user()->id;
                    Mesin::create($arrData);
                }
                
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
            Mesin::find($req['sId'])->delete();
            
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
        
        $datas   = Mesin::with('createdby', 'updatedby', 'wc');  
        
        if(!empty($req['search']))
        {
            $datas->where(function($q) use($req)
            {
                $q->where('nama', 'like',str_replace('*','%',$req['search']));
                $q->orWhere('merek', 'like', str_replace('*','%',$req['search']));
            });
        }

        if(!empty($req['wc']))
        {
            $datas->where('wc_id', $req['wc']);
        }

        $datas->orderBy('id','asc');
        
        return  Datatables::of($datas)
                ->editColumn('id', '{{$id}}')
                ->make(true);
    }
    
    public function select(Request $request)
    {
        $tags = null;
        
        $term = trim($request->input('q'));
        $tags = Mesin::where('merek','like', '%'.$term.'%')->limit(50)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->merek.' - '.$tag->proses];
        }

        return response()->json(array('items' => $formatted_tags), 200);
    }
}
