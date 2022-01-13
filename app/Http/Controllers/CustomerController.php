<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\MasterOption;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Auth;
use Validator;

use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.master.customer.index');
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
                'nama1'      => 'required',
                'country_id'      => 'required',
                'group_id'      => 'required',
                'kota'      => 'required',
            ],
            [
                'kode.required'  => 'Kode harus diisi.',
                'nama1.required'     => 'Nama 1 harus diisi.',
                'country_id.required'     => 'Country harus diisi.',
                'group_id.required'     => 'Group harus diisi.',
                'kota.required'     => 'Kota harus diisi.',
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
                    
                    Customer::create($req);
                    
                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil disimpan'
                    ));
                }
                else
                {
                    $req['updated_by']   = Auth::user()->id;        
                    $req['updated_at']   = Carbon::now();

                    Customer::find($req['id'])->fill($req)->save();
                    
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
     * @param  \Illuminate\Http\Request  $request
     *
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

                    //kalau customer kosong di lewati
					if(!$sD[$arrKey->customer]) continue;

                    $country = MasterOption::where('kode', $sD[$arrKey->country])->where('tipe', 'COUNTRY')->first();
                    $group   = MasterOption::where('kode', $sD[$arrKey->group])->where('tipe', 'ACCGROUP')->first();
                                        
                    $arrData = [
                        'kode' => $sD[$arrKey->customer],
                        'nama1' => $sD[$arrKey->nama1],
                        'nama2' => $sD[$arrKey->nama2],
                        'kota' => $sD[$arrKey->kota],
                        'jalan1' => $sD[$arrKey->jalan1],
                        'jalan2' => $sD[$arrKey->jalan2],
                        'updated_by' => Auth::user()->id
                    ];

                    if($country) $arrData['country_id'] = $country->id; else continue;
                    if($group) $arrData['group_id'] = $group->id; else continue;

                    $prev = Customer::where('kode', $sD[$arrKey->customer])->first();

                    if($prev)
                    {
                        Customer::find($prev->id)->fill($arrData)->save();
                    }
                    else
                    {
                        $arrData['created_by'] = Auth::user()->id;
                        Customer::create($arrData);
                    }
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
            Customer::find($req['sId'])->delete();
            
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
        
        $datas   = Customer::with('createdby', 'updatedby', 'country', 'group');  
        
        if(!empty($req['search']))
        {
            $datas->where(function($q) use($req)
            {
                $q->where('kode', 'like',str_replace('*','%',$req['search']));
                $q->orWhere('nama1', 'like', str_replace('*','%',$req['search']));
                $q->orWhere('nama2', 'like', str_replace('*','%',$req['search']));
            });
        }

        if(!empty($req['skota']))
		{
			$datas->where('kota', 'like', str_replace('*','%',$req['skota']));
		}

        if(!empty($req['country']))
        {
            $datas->where('country_id', $req['country']);
        }

        if(!empty($req['group']))
        {
            $datas->where('group_id', $req['group']);
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
        $tags = Customer::where('kode','like', '%'.$term.'%')->limit(50)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->kode, 'nama' => $tag->nama1];
        }

        return response()->json(array('items' => $formatted_tags), 200);
    }
}
