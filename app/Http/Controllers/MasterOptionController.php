<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterOption;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Auth;
use Validator;


class MasterOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.administrator.master_option.index');
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
                    'deskripsi'   => 'required',
                    'tipe'   => 'required',
                ],
                [
                    'kode.required'  => 'Kode harus diisi.',
                    'deskripsi.required'  => 'Deskripsi harus diisi.',
                    'tipe.required'  => 'Tipe harus diisi.',
                ]);

            if($validation->fails())
            {
                echo json_encode(array(
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
                    $req['updated_at']   = Carbon::now();
                    $req['created_by']   = Auth::user()->id;
                    $req['created_at']   = Carbon::now();
                    
                    MasterOption::create($req);
                    
                    echo json_encode(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil disimpan'
                    ));
                }
                else
                {
                    $req['updated_by']   = Auth::user()->id;        
                    $req['updated_at']   = Carbon::now();
                    MasterOption::find($req['id'])->fill($req)->save();
                    
                    echo json_encode(array(
                        'status' => 1,
                        'msg'   => 'Data berhasil diubah'
                    ));
                    
                }
            }
        }
        catch (QueryException $er)
        {
            echo json_encode(array(
                'status' => 0,
                'msg'   => 'Data gagal disimpan'
            ));
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
            MasterOption::find($req['sId'])->delete();
            
            echo json_encode(array(
               "status" => 1,
                "msg"   => "Data berhasil dihapus."
            ));
        } 
        catch (QueryException $ex) 
        {
            echo json_encode(array(
               "status" => 0,
                "msg"   => "Data gagal dihapus."
            ));
        }
    }
    
    public function dt(Request $request)
    {
        $req    = $request->all();
        
        $datas   = MasterOption::with(['createdBy']);  
        
        if(!empty($req['sSearch']))
        {
            $datas->where(function($q) use($req)
            {
                $q->where('deskripsi', 'like', str_replace('*','%',$req['sSearch']));
                $q->orWhere('kode', 'like', str_replace('*','%',$req['sSearch']));
            });
        }

        if(!empty($req['sTipe']))
        {
            $datas->where('tipe', $req['sTipe']);
        }

        $datas->orderBy('id','desc');
        
        return  Datatables::of($datas)
                ->editColumn('id', '{{$id}}')
                ->make(true);
    }

    public function selectTypeUser(Request $request)
    {
        return response()->json($this->select2($request, 'USERTYPE'), 200);
    }

    public function selectCountry(Request $request)
    {
        return response()->json($this->select2($request, 'COUNTRY'), 200);
    }

    public function selectAccGroup(Request $request)
    {
        return response()->json($this->select2($request, 'ACCGROUP'), 200);
    }

    public function selectMrpGroup(Request $request)
    {
        return response()->json($this->select2($request, 'MRPGROUP'), 200);
    }

    public function selectMatType(Request $request)
    {
        return response()->json($this->select2($request, 'MATTYPE'), 200);
    }

    public function selectBUnit(Request $request)
    {
        return response()->json($this->select2($request, 'BUNIT'), 200);
    }

    public function selectMatGroup(Request $request)
    {
        return response()->json($this->select2($request, 'BUNIT'), 200);
    }

    public function selectValCl(Request $request)
    {
        return response()->json($this->select2($request, 'VALCL'), 200);
    }

    public function selectGroup(Request $request)
    {
        return response()->json($this->select2($request, 'ACCGROUP'), 200);
    }

    public function selectWc(Request $request)
    {
        return response()->json($this->select2($request, 'WC'), 200);
    }
    
    public function selectTipe(Request $request)
    {
        $tags = null;
        
        $term = trim($request->input('q'));
        $tags = MasterOption::where('tipe','like', '%'.$term.'%')->limit(50)->groupBy('tipe')->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->tipe, 'text' => $tag->tipe];
        }

        return array('items' => $formatted_tags);
    }
    
    private function select2($request, $tipe, $limit = 100)
    {
        $tags = null;
        
        $term = trim($request->input('q'));
        $tags = MasterOption::where(function($q) use($term)
        {
            $q->where('kode','like','%'.$term.'%')
              ->orWhere('deskripsi','like','%'.$term.'%')
              ->orWhere('id',$term);
        })->where('tipe',$tipe)->limit($limit)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->kode, 'deskripsi' => $tag->deskripsi];
        }

        return array('items' => $formatted_tags);
    }
}
