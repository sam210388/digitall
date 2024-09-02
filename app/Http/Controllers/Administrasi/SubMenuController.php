<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\MenuModel;
use App\Models\Administrasi\SubMenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SubMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $judul = 'Data Sub Menu';
        $datamenu = MenuModel::all();
        if ($request->ajax()) {
            $data = SubMenuModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editsubmenu">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletesubmenu">Delete</a>';

                    return $btn;
                })
                ->addColumn('idmenu',function ($row){
                    $idmenu = $row->idmenu;
                    $uraianmenu = DB::table('menu')->where('id','=',$idmenu)->value('uraianmenu');
                    return $uraianmenu;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.submenu',[
            "judul"=>$judul,
            "datamenu" => $datamenu
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
            'idmenu' => 'required',
            'uraiansubmenu' => 'required|max:200',
            'url_submenu' => 'required|max:200',
            'icon_submenu' => 'required|max:200'
        ]);
        SubMenuModel::create(
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
        SubMenuModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
