<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ruangan;

class ReservasiController extends Controller
{
    public function stepOne(Request $request){
        $reservasi = $request->session()->get('reservasi');
        return view ('user.reservasi', compact('reservasi'));
    }

    public function createOne(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|max:100',
            'reserverid' => 'required',
            'contactnumber' => 'required|max:12',
            'email' => 'required|email',
        ],
        [
            'fullname.required' => 'Nama tidak boleh kosong!',
            'reserverid.required' => 'NRP tidak boleh kosong!',
            'contactnumber.required' => 'Nomor telepon tidak boleh kosong!',
            'email.required' => 'Email tidak boleh kosong!',
        ]);
  
        if(empty($request->session()->get('reservasi'))){
            $reservasi = new Reservasi();
            $reservasi->fill($validatedData);
            $request->session()->put('reservasi', $reservasi);
        }else{
            $reservasi = $request->session()->get('reservasi');
            $reservasi->fill($validatedData);
            $request->session()->put('reservasi', $reservasi);
        }
  
        return redirect()->route('penanggungJawab');
    }

    public function stepTwo(Request $request){
        $reservasi = $request->session()->get('reservasi');
        return view ('user.reservasi2',compact('reservasi'));
    }

    public function createTwo(Request $request)
    {
         $request->validate([
            'mainpicposition' => 'required',
            'mainpicname' => 'required',
            'suratpath' => 'required|mimes:pdf|max:4096',
        ],
        [
            'mainpicposition.required' => 'Posisi PIC tidak boleh kosong!',
            'mainpicname.required' => 'Nama PIC tidak boleh kosong!',
            'suratpath.required' => 'Surat tidak boleh kosong!'
        ]);

        $reservasi = $request->session()->get('reservasi');
        $request->suratpath->store('surat', 'public');
        
        $reservasi->fill([
            'mainpicposition' => $request->mainpicposition,
            'mainpicname' => $request->mainpicname,
            'suratpath' => $request->suratpath->hashName(),
        ]);
        $request->session()->put('reservasi', $reservasi);
  
  
        return redirect()->route('detailPeminjaman');
    }

    public function selectRoom(Request $request){
        $roomname = $request->roomname;
        dd($roomname);
        return view ('user.reservasi3');
    }
    


    public function stepThree(Request $request){
        $reservasi = $request->session()->get('reservasi');
        $lantais = Ruangan::select('floornum')->distinct('floornum')->where('id','!=',NULL)->get();
        // $ruangans = Ruangan::all();
        return view ('user.reservasi3',compact('reservasi','lantais'));
    }


    public function detailPeminjamanAjax(Request $request){
        $datas['ruangan'] = Ruangan::where("floornum", $request->floornum)
        ->get(["roomname"]);

return response()->json($datas);
    }


    public function createThree(Request $request)
    {
       
        $validatedData = $request->validate([
            'floornum' => 'required',
            'roomname' => 'required',
            'reservationstart' => 'required',
            'reservationend' => 'required',
        ],
        [
            'floornum.required' => 'Lantai tidak boleh kosong!',
            'roomname.required' => 'Nama ruangan tidak boleh kosong!',
            'reservationstart.required' => 'Tanggal dan jam mulai tidak boleh kosong!',
            'reservationend.required' => 'Tanggal dan jam selesai tidak boleh kosong!',
        ]);

        // dd(request()->all());
        $resValidator = Event::query()
        ->where('lantai', $request->floornum)
        ->where('ruangan', $request->roomname)
        ->get(['start', 'end']);

        $start = Carbon::parse($request->reservationstart)->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->reservationend)->format('Y-m-d H:i:s');

        foreach ($resValidator as $validator) {
            if($validator->start <= $start && $start <= $validator->end){
                return Redirect::back()->withErrors(['msg' => 'Ruang kelas sudah direservasi untuk waktu tersebut!']);
            }

            if($validator->start <= $end && $end <= $validator->end){
                return Redirect::back()->withErrors(['msg' => 'Ruang kelas sudah direservasi untuk waktu tersebut!']);
            }

            if($start <= $validator->start && $validator->end <= $end){
                return Redirect::back()->withErrors(['msg' => 'Ruang kelas sudah direservasi untuk waktu tersebut!']);
            }
        }

        $reservasi = $request->session()->get('reservasi');
        $reservasi->fill($validatedData);
        $request->session()->put('reservasi', $reservasi);
  
        return redirect()->route('detailKegiatan');
    }

    public function stepFour(Request $request){
        $reservasi = $request->session()->get('reservasi');
        return view ('user.reservasi4',compact('reservasi'));
    }

    public function createFour(Request $request)
    {
        $validatedData = $request->validate([
            'organization' => 'required',
            'eventname' => 'required',
            'eventcategory' => 'required',
            'eventdescription'=> 'required',
        ],
        [
            'organization.required' => 'Nama organisasi tidak boleh kosong!',
            'eventname.required' => 'Nama event tidak boleh kosong!',
            'eventcategory.required' => 'Katagori kegiatan tidak boleh kosong!',
            'eventdescription.required' => 'Deskripsi Kegiatan tidak boleh kosong!',
        ]);
  
        $reservasi = $request->session()->get('reservasi');
        $reservasi->fill($validatedData);
        $request->session()->put('reservasi', $reservasi);

        
        $reservasi->save();
  
        $request->session()->forget('reservasi');
  
        return redirect()->route('confirmed');
    }

    public function confirm(){
        return view ('user.reservasiConfirmed');
    }

    public function listReservasi(Request $request) {
        $data = Reservasi::where([
            ['reservationid', '!=', NULL]
        ])->where(function($query) use ($request){
            $query->where('fullname', 'LIKE', '%' . $request->term . '%');
        })->orderBy('reservationid', 'desc')->paginate(10);
        return view('admin.listReservasi', ['reservasis'=>$data]);
    }

    public function detailReservasi($id) {
        $data = Reservasi::where('reservationid','=',$id)->first();
        $date = Carbon::parse($data->reservationstart)->format('l, j F Y');
        $timestart = Carbon::parse($data->reservationstart)->format('H:i');
        $timeend = Carbon::parse($data->reservationend)->format('H:i');
        return view('admin.detailReservasi', ['reservasis'=>$data,'date'=>$date,'timestart'=>$timestart,'timeend'=>$timeend]);
    }

    public function terima(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'roomname' => 'required',
            'eventname' => 'required',
            'floornum' => 'required',
            'reservationstart' => 'required',
            'reservationend' => 'required',
        ]);
        Reservasi::where('reservationid', $id)->update([
            'status' => $request['status'],
        ]);
        if ($request['status']==2) {
            Event::create([
                'title'		=> $request['eventname'],
                'lantai'	=> $request['floornum'],
                'ruangan'	=> $request['roomname'],
                'start'     => $request['reservationstart'],
                'end'     => $request['reservationend'],
            ]);
        }

        return redirect()->route('listReservasi')->with('Sukses!','Reservasi telah diubah');
    }

}