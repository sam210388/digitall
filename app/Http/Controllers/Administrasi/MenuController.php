<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\Administrasi\MenuModel;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','aksesmenu']);
    }

    function tampillistmenu(Request $request){
        $userid = Auth::id();
        $idkewenangan = DB::table('role_users')->where('iduser','=',$userid)->value('idrole');
        $datamenu = DB::table('menu_kewenangan')
            ->join('menu',function($join){
                $join->on('menu_kewenangan.idmenu','=','menu.id')
                    ->where('menu.active','=','on');
            })
            ->where('idkewenangan','=',$idkewenangan)
            ->get();
        foreach ($datamenu as $menu) {
            $idmenu = $menu->idmenu;
            $datasubmenu = DB::table('submenu')->where('idmenu','=',$idmenu)->get();
            $jumlahsubmenu = $datasubmenu->count();
            if ($jumlahsubmenu > 0) {
                echo "<li class='nav-item'>".
                    "<a href='".url($menu->url_menu)."' class='nav-link active'>".
                    "<ion-icon name='".$menu->icon_menu."'></ion-icon>".
                    "<p>
                            ".$menu->uraianmenu."
                            <i class='right fas fa-angle-left'></i>
                        </p>".
                    "</a>"
                ;
                // sub menu nya disini
                echo "<ul class='nav nav-treeview'>";
                foreach ($datasubmenu as $sub) {
                    echo "<li class='nav-item'>".
                        "<a href='".url($sub->url_submenu)."' class='nav-link active'>".
                        "<ion-icon name='".$sub->icon_submenu."'></ion-icon>".
                        "<p>".
                        $sub->uraiansubmenu."
                        </p>".
                        "</a>
                            </li>";
                }
                echo "</ul>";
                echo "</li>";
            } else {
                echo "<li class='nav-item'>".
                    "<a href='".url($menu->url_menu)."' class='nav-link active'>".
                    "<ion-icon name='".$menu->icon_menu."'></ion-icon>".
                    "<p> ".$menu->uraianmenu."</p>".
                    "</a>
                           </li>";
            }
        }
    }

    public function index(Request $request)
    {
        $judul = 'Data Menu';
        if ($request->ajax()) {

            $data = MenuModel::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editmenu">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletemenu">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Administrasi.menu',[
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
        if ($request->get('active') == null){
            $active = "off";
        }else{
            $active = "on";
        }
        MenuModel::updateOrCreate(
            ['id' => $request->get('idmenu')],
            [
                'uraianmenu' => $request->get('uraianmenu'),
                'url_menu' => $request->get('url_menu'),
                'icon_menu' => $request->get('icon_menu'),
                'active' => $active
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
        $menu = MenuModel::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cek apakah ada kewenangan sudah dipakai
        $adadata = DB::table('submenu')->where('idmenu','=',$id)->count();
        if ($adadata == 0){
            MenuModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status' => 'gagal']);
        }

    }
}
