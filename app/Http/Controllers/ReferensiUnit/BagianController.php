<?php

namespace App\Http\Controllers\ReferensiUnit;

use App\Http\Controllers\Controller;
use App\Models\ReferensiUnit\BiroModel;
use App\Models\ReferensiUnit\DeputiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\ReferensiUnit\BagianModel;

class BagianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    function importunit(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://siap.dpr.go.id/api-rest/arbeitseinheit',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'geheimagent=s4mb3n3k3YP4ttY',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: PHPSESSID=t7fanhl58pqh4hnhfdipidh8f1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo json_encode($response);
        $hasil = json_decode($response);

        //hapus dlu tabel nya
        DB::table('apisiapunit')->truncate();

        foreach ($hasil as $item){
            $generated_path = $item->generated_path;
            $id = $item->id;
            $id_satker_baru = $item->id_satker_baru;
            $id_unor = $item->id_unor;
            $nama_satker = $item->nama_satker;
            $id_parent = $item->id_parent;
            $alamat = $item->alamat;
            $eselon = $item->eselon;
            $id_eselon1 = $item->id_eselon1;

            $data = array(
                'generated_path' => $generated_path,
                'id' => $id,
                'id_satker_baru' => $id_satker_baru,
                'id_unor' => $id_unor,
                'nama_satker' => $nama_satker,
                'id_parent' => $id_parent,
                'alamat' => $alamat,
                'eselon' => $eselon,
                'id_eselon1' => $id_eselon1
            );
            DB::table('apisiapunit')->updateOrInsert(['id' => $id],$data);
        }
        $this->rekapunitkerja();
        return redirect()->to('bagian')->with('status',"Import API Berhasil");
    }

    function rekapunitkerja(){
        //DEPUTI
        //HAPUS DLU TABEL DEPUTI
        DB::table('deputi')->truncate();

        //dapatkan data deputi
        $datadeputi = DB::table('apisiapunit')->where('eselon','=',1)->get();
        foreach ($datadeputi as $deputi){
            $id = $deputi->id;
            $uraiandeputi = $deputi->nama_satker;
            $status = "on";

            $data = array(
                'id' => $id,
                'uraiandeputi' => $uraiandeputi,
                'status' => "on"
            );

            DB::table('deputi')->insert($data);
        }

        //BIRO
        //HAPUS DLU TABEL BIRO
        DB::table('biro')->truncate();

        //dapatkan data biro
        $databiro = DB::table('apisiapunit')->where('eselon','=',2)->get();

        foreach ($databiro as $biro){
            $id = $biro->id;
            $iddeputi = $biro->id_parent;
            $uraianbiro = $biro->nama_satker;
            $status = "on";

            $data = array(
                'id' => $id,
                'iddeputi' => $iddeputi,
                'uraianbiro' => $uraianbiro,
                'status' => "on"
            );

            DB::table('biro')->insert($data);
        }

        //BAGIAN
        //HAPUS DLU TABEL BAGIAN
        DB::table('bagian')->truncate();

        //dapatkan data bagian
        $databagian = DB::table('apisiapunit')->where('eselon','=',3)->get();
        foreach ($databagian as $bagian){
            $id = $bagian->id;
            $idbiro = $bagian->id_parent;
            $iddeputi = DB::table('biro')->where('id','=',$idbiro)->value('iddeputi');
            if ($iddeputi == null){
                $iddeputi = $idbiro;
            }
            $uraianbagian = $bagian->nama_satker;
            $status = "on";

            $data = array(
                'id' => $id,
                'iddeputi' => $iddeputi,
                'idbiro' => $idbiro,
                'uraianbagian' => $uraianbagian,
                'status' => "on"
            );
            DB::table('bagian')->insert($data);
        }

    }

    public function dapatkandatabiro(Request $request){
        $data['biro'] = DB::table('biro')
            ->where('iddeputi','=',$request->iddeputi)
            ->get(['id','uraianbiro']);

        return response()->json($data);
    }

    public function dapatkandatabagian(Request $request){
        $data['bagian'] = DB::table('bagian')
            ->where('idbiro','=',$request->idbiro)
            ->get(['id','uraianbagian']);
        return response()->json($data);
    }

    public function index(Request $request)
    {
        $judul = 'List Bagian';
        $datadeputi = DeputiModel::all();
        $databiro = BiroModel::all();
        if ($request->ajax()) {
            $data = BagianModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editbagian">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletebagian">Delete</a>';

                    return $btn;
                })
                ->addColumn('iddeputi',function ($row){
                    $iddeputi = $row->iddeputi;
                    $uraiandeputi = DB::table('deputi')->where('id','=',$iddeputi)->value('uraiandeputi');
                    return $uraiandeputi;
                })
                ->addColumn('idbiro',function ($row){
                    $idbiro = $row->idbiro;
                    $uraianbiro = DB::table('biro')->where('id','=',$idbiro)->value('uraianbiro');
                    return $uraianbiro;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('ReferensiUnit.bagian',[
            "judul"=>$judul,
            "datadeputi" => $datadeputi,
            "databiro" => $databiro
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
            $status = "off";
        }else{
            $status = "on";
        }
        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'uraianbagian' => 'required',

        ]);

        BagianModel::create(
            [
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'uraianbagian' => $request->get('uraianbagian'),
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
        $menu = BagianModel::find($id);
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
            $status = "off";
        }else{
            $status = "on";
        }
        $validated = $request->validate([
            'iddeputi' => 'required',
            'idbiro' => 'required',
            'uraianbagian' => 'required',

        ]);

        BagianModel::where('id',$id)->update(
            [
                'iddeputi' => $request->get('iddeputi'),
                'idbiro' => $request->get('idbiro'),
                'uraianbagian' => $request->get('uraianbagian'),
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
        $dipakai = DB::table('temuan')->where('idbagian','=',$id)->count();
        if ($dipakai == 0){
            BagianModel::find($id)->delete();
            return response()->json(['status'=>'berhasil']);
        }else{
            return response()->json(['status'=>'gagal']);
        }

    }
}
