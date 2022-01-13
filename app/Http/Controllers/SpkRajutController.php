<?php

namespace App\Http\Controllers;

use App\Models\SpkRajut;
use App\Models\SpkRajutDetail;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Auth;
use Validator;

use DB;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SpkRajutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.transaction.spk_rajut.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $var = new SpkRajut;

        return view('app.transaction.spk_rajut.form', compact('var'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $req = $request->all();
            
            $validation = Validator::make($req, 
            [
                'no'      => [
                    'required',
                    Rule::unique('spk_rajuts')->ignore($req['id'])
                ],
                'tanggal'      => 'required',
                'customer_id'      => 'required',
            ],
            [
                'no.required'     => 'No SPK harus diisi.',
                'no.unique'     => 'No SPK Sudah ada.',
                'tanggal.required'     => 'Tanggal harus diisi.',
                'customer_id.required'     => 'Customer harus diisi.',
            ]);

            if($validation->fails())
            {
                DB::rollback();
                return response()->json(array(
                    'status' => 0,
                    'msg'   => $validation->errors()->all()
                ));
            }
            else
            {
                if(empty($req['id']))
                {
                    $req['updated_by']   = Auth::user()->id;
                    $req['created_by']   = Auth::user()->id;
                    $req['status_at']    = Carbon::now();

                    $history = json_encode(['status' => 'unprosessed', 
                                            'status_at' => Carbon::now()->toDateTimeString(),
                                            'created_by' => ['id' => Auth::user()->id, 'name' => Auth::user()->name]]);
                    $req['history'] =  $history;

                    $idSave = SpkRajut::create($req);

                    if($idSave)
                    {
                        SpkRajutDetail::whereNull('spk_rajut_id')
                                      ->where('created_by', Auth::user()->id)
                                      ->update(['spk_rajut_id' => $idSave->id]);

                        $totalQty = SpkRajutDetail::where('spk_rajut_id', $idSave->id)->sum('qty');

                        SpkRajut::find($idSave->id)->update(['total_qty' => $totalQty]);
                        DB::commit();

                        return response()->json(array(
                            'status' => 1,
                            'msg'   => 'Data berhasil disimpan'
                        ));
                    }
                    else
                    {
                        DB::rollback();
                        return response()->json(array(
                            'status' => 0,
                            'msg'   => 'Data gagal disimpan'
                        ));
                    }
                    
                }
                else
                {
                    $req['updated_by']   = Auth::user()->id;        
                    $req['updated_at']   = Carbon::now();

                    SpkRajut::find($req['id'])->fill($req)->save();
                    DB::commit();

                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil diubah'
                    ));
                    
                }
            }
        }
        catch (QueryException $er)
        {
            DB::rollback();
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
    public function storeDetail(Request $request)
    {
        DB::beginTransaction();
        $req = $request->all();
        try
        {
            $validation = Validator::make($req, 
                [
                    'det_mesin_id'   => 'required',
                    'det_material_id'   => 'required',
                    'det_raw_material_id'      => 'required',
                    'det_greige'      => 'required',
                    'det_finish'      => 'required',
                    'det_size_finish'      => 'required',
                    'det_qty'      => 'required',
                    // 'det_total_qty'      => 'required',
                ],
                [
                    'det_mesin_id.required'  => 'Mesin harus diisi.',
                    'det_material_id.required'     => 'Material harus diisi.',
                    'det_raw_material_id.required'     => 'Raw Material harus diisi.',
                    'det_greige.required'  => 'Nilai Greige diisi.',
                    'det_finish.required'     => 'Nilai Finish harus diisi.',
                    'det_size_finish.required'     => 'Nilai Size Finish harus diisi.',
                    'det_qty.required'  => 'Qty harus diisi.',
                    // 'det_total_qty.required'     => 'Relasi harus diisi.',
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
                
                $arr = [
                    'mesin_id' => $req['det_mesin_id'],
                    'material_id'   => $req['det_material_id'],
                    'material_raw_id' => $req['det_raw_material_id'],
                    'warna' => $req['det_warna'],
                    'greige' => $req['det_greige'],
                    'finish' => $req['det_finish'],
                    'size_finish' => $req['det_size_finish'],
                    'qty' => $req['det_qty'],
                    'spk_rajut_id' => (!empty($req['spk_rajut_id'])?$req['spk_rajut_id']:null),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                
                
                if(empty($req['detail_id']))
                {                                        
                    $id = SpkRajutDetail::create($arr);

                    if(!empty($req['spk_rajut_id']))
                    {
                        $totalQty = SpkRajutDetail::where('spk_rajut_id', $req['spk_rajut_id'])->sum('qty');

                        SpkRajut::find($req['spk_rajut_id'])->update(['total_qty' => $totalQty]);
                    }

                    DB::commit();                    
                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil disimpan'
                    ));
                }
                else
                {
                    unset($arr['created_by']);
                    SpkRajutDetail::find($req['detail_id'])->fill($arr)->save();
                    
                    if(!empty($req['spk_rajut_id']))
                    {
                        $totalQty = SpkRajutDetail::where('spk_rajut_id', $req['spk_rajut_id'])->sum('qty');

                        SpkRajut::find($req['spk_rajut_id'])->update(['total_qty' => $totalQty]);
                    }

                    DB::commit();

                    return response()->json(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil diubah'
                    ));
                    
                }
            }
        }
        catch (QueryException $er)
        {
            DB::rollback();
            return response()->json(array(
                'status' => 0,
                'msg'   => 'Data gagal disimpan'.$er->getMessage()
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpkRajut  $spkRajut
     * @return \Illuminate\Http\Response
     */
    public function show(SpkRajut $spkRajut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpkRajut  $spkRajut
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $var = SpkRajut::find($id);
        
        return view('app.transaction.spk_rajut.form', compact('var')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SpkRajut  $spkRajut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpkRajut $spkRajut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpkRajut  $spkRajut
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $req = $request->all();
        try 
        {
            SpkRajut::find($req['id'])->delete();
            
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpkRajut  $spkRajut
     * @return \Illuminate\Http\Response
     */
    public function destroyDetail(Request $request)
    {
        $req = $request->all();
        try 
        {
            SpkRajutDetail::find($req['id'])->delete();
            
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
        
        $datas   = SpkRajut::with('createdby', 'updatedby', 'details', 'customers');  
        
        if(!empty($req['search']))
        {
            $datas->where('no', 'like',str_replace('*','%',$req['search']));
        }

        if(!empty($req['stanggal']))
        {
            $datas->where('tanggal', $req['search']);
        }

        if(!empty($req['scustomer']))
        {
            $datas->where('customer_id', $req['scustomer']);
        }

        $datas->orderBy('id','desc');
        
        return  Datatables::of($datas)
                ->editColumn('id', '{{$id}}')
                ->make(true);
    }
    
    public function dtDetail(Request $request)
    {
        $req    = $request->all();
        
        $datas   = SpkRajutDetail::with('createdby', 'updatedby', 'mesin', 'material', 'rawmaterial');  
        
        if(empty($req['id']))
        {
            $datas->whereNull('spk_rajut_id')->where('created_by', Auth::user()->id);
        }
        else
        {
            $datas->where('spk_rajut_id', $req['id']);
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
