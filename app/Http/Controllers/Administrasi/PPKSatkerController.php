<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\PPKSatkerModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PPKSatkerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data PPK';
        if ($request->ajax()) {
            $data = PPKSatkerModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.ppksatker',[
            "judul"=>$judul
        ]);
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
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $validated = $request->validate([
            'kodesatker' => 'required',
            'uraianppk' => 'required|max:200',
        ]);
        PPKSatkerModel::create(
            [
                'kodesatker' => $request->get('kodesatker'),
                'uraianppk' => $request->get('uraianppk'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = PPKSatkerModel::find($id);
        return response()->json($menu);
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
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $validated = $request->validate([
            'kodesatker' => 'required',
            'uraianppk' => 'required|max:200',
        ]);
        PPKSatkerModel::where('id',$id)->update(
            [
                'kodesatker' => $request->get('kodesatker'),
                'uraianppk' => $request->get('uraianppk'),
                'status' => $active
            ]);

        return response()->json(['status'=>'berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PPKSatkerModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
