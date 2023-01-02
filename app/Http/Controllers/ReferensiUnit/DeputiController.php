<?php

namespace App\Http\Controllers\ReferensiUnit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferensiUnit\DeputiModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DeputiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $this->authorize('view',DeputiModel::class);

        $judul = 'List Deputi';
        if ($request->ajax()) {
            $data = DeputiModel::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editdeputi">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletedeputi">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ReferensiUnit.deputi',[
            "judul"=>$judul
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
        $this->authorize('create',DeputiModel::class);

        if ($request->get('status') == null){
            $status = "off";
        }else{
            $status = "on";
        }

        $validated = $request->validate([
            'uraiandeputi' => 'required'
        ]);

        DeputiModel::create(
            [
                'uraiandeputi' => $request->get('uraiandeputi'),
                'status' => $status
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
        $this->authorize('update',DeputiModel::class);
        $deputi = DeputiModel::find($id);
        return response()->json($deputi);
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
        $this->authorize('update',DeputiModel::class);
        if ($request->get('status') == null){
            $status = "off";
        }else{
            $status = "on";
        }

        $validated = $request->validate([
            'uraiandeputi' => 'required'
        ]);

        DeputiModel::where('id',$id)->update(
            [
                'uraiandeputi' => $request->get('uraiandeputi'),
                'status' => $status
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
        $this->authorize('delete',DeputiModel::class);
        //cek apakah sudah dipakai buat biro
        $dipakai = DB::table('biro')->where('iddeputi','=',$id)->count();
        if ($dipakai > 0){
            return response()->json(['status'=>'datadipakai']);
        }else if ($dipakai == 0){
            DeputiModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
