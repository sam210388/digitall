<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Sub Menu';
        if ($request->ajax()) {
            $data = DB::table('area')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editsubmenu">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletesubmenu">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Sirangga.admin.area',[
            "judul"=>$judul
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodearea' => 'required|max:2',
            'uraianarea' => 'required|max:200',
        ]);
        DB::table('area')->insert(
            [
                'kodeara' => $request->get('kodearea'),
                'uraianarea' => $request->get('uraianarea')
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
        $this->authorize('update',SubMenuModel::class);
        $menu = SubMenuModel::find($id);
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
        $this->authorize('update',SubMenuModel::class);
        if ($request->get('status') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        $validated = $request->validate([
            'idmenu' => 'required',
            'uraiansubmenu' => 'required|max:200',
            'url_submenu' => 'required|max:200',
            'icon_submenu' => 'required|max:200'
        ]);
        SubMenuModel::where('id',$id)->update(
            [
                'idmenu' => $request->get('idmenu'),
                'uraiansubmenu' => $request->get('uraiansubmenu'),
                'url_submenu' => $request->get('url_submenu'),
                'icon_submenu' => $request->get('icon_submenu'),
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
        $this->authorize('delete',SubMenuModel::class);
        SubMenuModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
