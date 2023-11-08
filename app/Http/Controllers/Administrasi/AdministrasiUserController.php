<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\AdministrasiUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;


class AdministrasiUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    public function editpassword($id){
        DB::table('users')->where('id','=',$id)->update([
            'password' => Hash::make(261107)
        ]);
    }

    public function index(Request $request)
    {
        $judul = 'Kelola User';
        if ($request->ajax()) {
            $data = AdministrasiUserModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('gambaruser',function ($row){
                    if ($row->gambaruser == ""){
                        $gambar = '
                        <div class="input-group">
                            <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <div class="user-panel">
                                <div class="image">
                                <img src="'.asset('storage')."/gambaruser/default.png".'" class="img-circle elevation-2" alt="User Image">
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                    }else{
                        $gambar = '
                        <div class="input-group">
                            <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <div class="user-panel">
                                <div class="image">
                                <img src="'.asset('storage')."/".$row->gambaruser.'" class="img-circle elevation-2" alt="User Image">
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                    }
                    return $gambar;

                })
                ->addColumn('deputi',function($row){
                    $iddeputi = $row->iddeputi;
                    if ($iddeputi != 0){
                        $uraiandeputi = DB::table('deputi')->where('id','=',$iddeputi)->value('uraiandeputi');
                    }else{
                        $uraiandeputi = 0;
                    }
                    return $uraiandeputi;

                })
                ->addColumn('biro',function($row){
                    $idbiro = $row->idbiro;
                    if ($idbiro != 0){
                        $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    }else{
                        $uraianbiro = 0;
                    }
                    return $uraianbiro;

                })
                ->addColumn('bagian',function($row){
                    $idbagian = $row->idbagian;
                    if ($idbagian != 0){
                        $uraianbagian = DB::table('bagian')->where('id','=',$idbagian)->value('uraianbagian');
                    }else{
                        $uraianbagian = 0;
                    }
                    return $uraianbagian;

                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edituser">Edit</a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteuser">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action','gambaruser'])
                ->make(true);
        }

        $datapegawai = DB::table('pegawai')->get();
        return view('Administrasi.administrasiuser',[
            "judul"=>$judul,
            "datapegawai" => $datapegawai
        ]);

    }


    public function store(Request $request)
    {
        $saveBtn = $request->get('saveBtn');
        if ($saveBtn == "tambah"){
            $validated = $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|max:12',
                'email' => 'required|unique:users|email',
                'password' => 'confirmed|required|min:6',
                //'gambaruser' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            $username = $request->get('username');
            if ($username != ""){
                $id_satker = DB::table('pegawai')->where('email','=',$username)->value('id_satker');

                //check id_satker ke bagian
                $idsatkercekbagian = DB::table('bagian')->where('id','=',$id_satker)->count();
                if ($idsatkercekbagian != 0){
                    $idbagian = $id_satker;
                    $idbiro = DB::table('bagian')->where('id','=',$idbagian)->value('idbiro');
                    $iddeputi = DB::table('bagian')->where('id','=',$idbagian)->value('iddeputi');
                }else if ($idsatkercekbagian == 0){
                    //check ke biro
                    $idsatkercekbiro = DB::table('biro')->where('id','=',$id_satker)->count();
                    if ($idsatkercekbiro != 0){
                        $idbiro = $id_satker;
                        $iddeputi = DB::table('biro')->where('id','=',$idbiro)->value('iddeputi');
                        $idbagian = 0;
                    }
                }else{
                    $iddeputi = $id_satker;
                    $idbiro = 0;
                    $idbagian = 0;
                }
            }else{
                $iddeputi = 0;
                $idbiro = 0;
                $idbagian =0;
            }

            $name = $request->get('name');
            $email = $request->get('email');
            $password = Hash::make($request->get('password'));
            $pnsppnpn = $request->get('pnsppnpn');
            $phone = $request->get('phone');


            if ($request->file('gambaruser')){
                $gambaruser = $request->file('gambaruser')->store(
                    'gambaruser','public');
            }else{
                $gambaruser = "gambaruser/default.png";
            }
            AdministrasiUserModel::create([
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'phone' => $phone,
                'password' => $password,
                'gambaruser' => $gambaruser,
                'pnsppnpn' => $pnsppnpn,
                'iddeputi' => $iddeputi,
                'idbiro' => $idbiro,
                'idbagian' => $idbagian
            ]);
            return response()->json(['status'=>'berhasil']);
        }

    }

    public function update(Request $request, $id){
        $saveBtn = $request->get('saveBtn');
        if ($saveBtn == 'edit'){
            $name = $request->get('name');
            $email = $request->get('email');
            $password = $request->get('password');
            $gambarlama = $request->get('gambarlama');
            $phone = $request->get('phone');

            if ($request->file('gambaruser') != ""){
                if (file_exists(storage_path('app/public/').$gambarlama)){
                    Storage::delete('public/'.$gambarlama);
                }
                $gambaruser = $request->file('gambaruser')->store(
                    'gambaruser','public'
                );
            }else{
                $gambaruser = $gambarlama;
            }

            if ($password != ""){
                $validated = $request->validate([
                    'name' => 'required|max:100',
                    'email' => 'required|email|unique:users,email,'.$id,
                    'password' => 'confirmed|min:6'
                ]);
                $password = Hash::make($password);
                AdministrasiUserModel::where('id','=',$id)->update([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'gambaruser' => $gambaruser,
                    'phone' => $phone
                ]);
                return response()->json(['status'=>'berhasil']);

            }else if ($password == ""){
                $validated = $request->validate([
                    'name' => 'required|max:100',
                    'email' => 'required|email|unique:users,email,'.$id,
                ]);
                AdministrasiUserModel::where('id','=',$id)->update([
                    'name' => $name,
                    'email' => $email,
                    'gambaruser' => $gambaruser,
                    'phone' => $phone
                ]);
                return response()->json(['status'=>'berhasil']);
            }
        }
    }

    public function edit($id)
    {
        $menu = AdministrasiUserModel::find($id);
        return response()->json($menu);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gambaruser = DB::table('users')->where('id','=',$id)->value('gambaruser');

        if (file_exists(storage_path('app/public/').$gambaruser)){
            Storage::delete('public/'.$gambaruser);
        }
        AdministrasiUserModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);

    }
}
