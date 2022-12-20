<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\AdministrasiUserModel;
use App\Policies\AdministrasiUserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;


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
        $this->authorize('view', AdministrasiUserModel::class);

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
                                <img src="'.asset('gambaruser/default.png').'" class="img-circle elevation-2" alt="User Image">
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
                                <img src="'.asset('gambaruser')."/".$row->gambaruser.'" class="img-circle elevation-2" alt="User Image">
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        ';
                    }
                    return $gambar;

                })
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edituser">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteuser">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action','gambaruser'])
                ->make(true);
        }

        return view('Administrasi.administrasiuser',[
            "judul"=>$judul
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize(['create','update'], AdministrasiUserModel::class);

        $saveBtn = $request->get('saveBtn');
        if ($saveBtn == "tambah"){
            $validated = $request->validate([
                'name' => 'required|max:100',
                'email' => 'required|unique:users|email',
                'password' => 'confirmed|required|min:6',
                'gambaruser' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            $name = $request->get('name');
            $email = $request->get('email');
            $password = Hash::make($request->get('password'));

            if ($request->file('gambaruser')){
                $file = $request->file('gambaruser');
                $filename = $email.".".$file->getClientOriginalExtension();
                $file->move(public_path('assets/gambaruser/'),$filename);
                $gambaruser = $filename;
            }

            AdministrasiUserModel::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'gambaruser' => $gambaruser
            ]);
            return response()->json(['status'=>'berhasil']);
        }else{
            $name = $request->get('name');
            $email = $request->get('email');
            $password = $request->get('password');
            $id = $request->get('id');
            $gambarlama = $request->get('gambarlama');

            if ($request->file('gambaruser') != ""){
                File::delete(public_path('assets/gambaruser')."/".$gambarlama);
                $file = $request->file('gambaruser');
                $filename = $email.".".$file->getClientOriginalExtension();
                $file->move(public_path('assets/gambaruser/'),$filename);
                $gambaruser = $filename;
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
                    'gambaruser' => $gambaruser
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
                    'gambaruser' => $gambaruser
                ]);
                return response()->json(['status'=>'berhasil']);
            }
        }
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
        $this->authorize('view', AdministrasiUserModel::class);
        $menu = AdministrasiUserModel::find($id);
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', AdministrasiUserModel::class);
        
        $gambaruser = AdministrasiUserModel::find($id)->value('gambaruser');
        File::get(public_path('gambaruser/'.$gambaruser));
        AdministrasiUserModel::find($id)->delete();
        return response()->json(['status'=>'berhasil']);
    }
}
