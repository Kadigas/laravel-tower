<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\uploadJadwal;
use App\Exports\uploadJadwalExport;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Petunjuk;
use Illuminate\Console\View\Components\Alert;

class AdminController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ruanganss = Ruangan::all();
            $eventss = Event::query()
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->get(['ruangan', 'lantai']);
            $user = Auth::user();
            $events = Event::count();
            $ruangans = Ruangan::count();
            $lantai = DB::table('ruangans')->distinct('floornum')->orderBy('floornum','asc')->get('floornum');
            $reservasis = Reservasi::count();
            $reservasisTerima = Reservasi::where('status','=',2)->count();
            $reservasisPending = Reservasi::where('status','=',1)->count();
            $reservasisTolak = Reservasi::where('status','=',3)->count();

            $reservasisTerimaChart = Reservasi::where('status','=',2)->whereYear('reservasis.reservationstart', 
            Carbon::now()->year)->whereMonth('reservasis.reservationstart', Carbon::now()->month)->count();
            $reservasisTolakChart = Reservasi::where('status','=',3)->whereYear('reservasis.reservationstart', 
            Carbon::now()->year)->whereMonth('reservasis.reservationstart', Carbon::now()->month)->count();
            
            $categories = [];
            $floorcat = [];
            $datas = [];
            $datares = [];
            $datalantai = [];
            
            // dd($lantai);

            foreach($ruanganss as $ru){
                $categories[] = $ru->roomname;
            } //roomname categories
            foreach($lantai as $lt){
                $floorcat[] = 'Lantai'.' '.$lt->floornum;
            } //lantai categories

            // dd($floorcat);
    
            $coba = Ruangan::query()->select('ruangans.roomname',
            DB::raw('COUNT(case when Month(start) = Month(curdate()) AND Year(start) = Year(curdate())  then 1 else null end) as jres'))
            // ->whereYear('reservasis.reservationstart', Carbon::now()->year)
            // ->whereMonth('reservasis.reservationstart', Carbon::now()->month)
            ->leftJoin('events','events.ruangan','ruangans.roomname')
            ->groupBy('events.ruangan')
            ->orderBy('ruangans.roomname', 'ASC')
            ->get();

            foreach($coba as $co){
                $datas[] = $co->jres;
            }

            $reservasicoba = Ruangan::query()->select('ruangans.roomname',
            DB::raw('COUNT(case when Month(reservasis.reservationstart) = Month(curdate()) AND Year(reservasis.reservationstart) = Year(curdate())  then 1 else null end) as jres'))
            // ->whereYear('reservasis.reservationstart', Carbon::now()->year)
            // ->whereMonth('reservasis.reservationstart', Carbon::now()->month)
            ->leftJoin('reservasis','reservasis.roomname','ruangans.roomname')
            ->groupBy('reservasis.roomname')
            ->orderBy('ruangans.roomname', 'ASC')
            ->get();

            foreach($reservasicoba as $reco){
                $datares[] = $reco->jres;
            }

            $cobalantai = Event::query()->select('events.lantai',
            DB::raw('COUNT(case when Month(start) = Month(curdate()) AND Year(start) = Year(curdate())  then 1 else null end) as jres'))
            // ->leftJoin('events','events.lantai','ruangans.floornum')
            ->groupBy('events.lantai')
            ->orderBy('events.lantai', 'ASC')
            ->get();

            foreach($cobalantai as $lt){
                $datalantai[] =  $lt->jres;
            }

            // dd($datalantai);
            
            // dd($co);
            

            if ($user->role == 'Admin'){
                
                return view('admin.dashboardAdmin',  compact('categories', 'datas','datares','events','reservasis', 'ruangans', 'reservasisTerima', 'reservasisTolak', 'reservasisPending','reservasisTerimaChart', 'reservasisTolakChart', 'ruanganss', 'eventss','datares','datalantai','floorcat'));
            }
        }
        return redirect('admin.login');
    }

    public function loginPage() {
        return view('admin.login');
    }
    public function testingMap() {
        return view('testingMapReserve');
    }

    public function login(Request $request){
        $request->validate([
             'email' => 'required',
             'password' => 'required',
         ]);
         $cridentials = $request->only('email','password');
        //  dd("berhasil login");
        //  dd(Auth::attempt($cridentials));
         if(Auth::attempt($cridentials)){
             // dd("salah");
             return redirect()->intended('admin')->withSuccess('Signed in');
         }
         return redirect('admin.login')->withErrors('Login details are not valid');
     }

     public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function uploadpetunjuk()
    {
        return view('admin.uploadPetunjuk');
    }

    public function viewClass()
    {
        return view('admin.ruanganIndex');
    }

    public function uploadpdf(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:pdf|max:4096'
            ]);
            $request->file->store('petunjuk', 'public');
            $petunjuk = new Petunjuk([
                "file_path" => $request->file->hashName()
            ]);
            $petunjuk->save(); 
           
            
            return redirect()->back()->with('success', 'petunjuk berhasil diunggah');
        }
        else {
            return redirect()->back()->with(['No file given']);
        }
    }

    public function uploadJadwal()
    {
        return view('admin.uploadJadwal');
    }

    public function fileImportExport()
    {
       return view('file-import');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileImport(Request $request) 
    {
        Excel::import(new uploadJadwal, $request->file('file')->store('temp'));
        return back();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileExport() 
    {
        return Excel::download(new uploadJadwalExport, 'report-list-reservasi.xlsx');
    }

    
     
}
