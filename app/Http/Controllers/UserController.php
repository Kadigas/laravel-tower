<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Petunjuk;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ruangan;

class UserController extends Controller
{
    public function home(){
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all();
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function ruanganView(){
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all();
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function lantai4() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 4);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 4)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function lantai5() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 5);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 5)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }


    public function lantai6() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 6);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 6)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function lantai7() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 7);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 7)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function lantai8() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 8);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 8)
        ->get(['ruangan', 'lantai']);

        return view('welcome', compact('ruangans', 'events'));
    }

    public function staffDisplay(){
        return view ('user.staffdisplay');
    }

    public function panduan(){
        $petunjuk = Petunjuk::orderBy('id', 'desc')->first();
        return view ('user.panduan',['petunjuk' => $petunjuk]);
    }

    public function jadwal(){
        $datas['lantai'] = Ruangan::distinct('floornum')->get(["floornum"]);
        // dd($datas);
        $events = array();
        $schedule = Event::all();
        foreach($schedule as $schedules){
            $events[] =[
                'title' => $schedules->title,
                'lantai' => $schedules->lantai,
                'ruangan' => $schedules->ruangan,
                'start' => $schedules->start,
                'end' => $schedules->end,
                
            ];
        }
        // if($request->ajax())
    	// {
    	// 	$data = Event::whereDate('start', '>=', $request->start)
        //                ->whereDate('end',   '<=', $request->end)
        //                ->get(['id', 'title', 'lantai', 'ruangan', 'start', 'end']);
        //     return response()->json($data);
    	// }
        // dd($events);
        return view ('user.jadwal',$datas,['event' => $events]);
    }
    public function jadwalAjax(Request $request){
        if($request->ajax()){
        $roomname = $request->ruangan;
        $events = array();
        if($roomname == "Semua"){
        $schedule = Event::All();    
        }else{
        $schedule = Event::where('ruangan',$roomname)->get(['title', 'lantai', 'ruangan', 'start', 'end']);
        }
        foreach($schedule as $schedules){
            $events[] =[
                'title' => $schedules->title,
                'lantai' => $schedules->lantai,
                'ruangan' => $schedules->ruangan,
                'start' => $schedules->start,
                'end' => $schedules->end,
                
            ];
        }
        // if($request->ajax())
    	// {
    	// 	$data = Event::whereDate('start', '>=', $request->start)
        //                ->whereDate('end',   '<=', $request->end)
        //                ->get(['id', 'title', 'lantai', 'ruangan', 'start', 'end']);
        //     return response()->json($data);
    	// }
        //  dd($events);
        return response()->json(['event'=>$events]);
        }
    }

    public function fetchruanganUser(Request $request)
    {
        $datas['ruangan'] = Ruangan::where("floornum", $request->floornum)
                                ->get(["roomname"]);
  
        return response()->json($datas);
    }

    public function kelasSatu(Request $request) 
	{
    	if($request->ajax())
    	{
    		$data = Event::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
					   ->where('kelas', 'LIKE', $request->kelas)
                       ->get(['id', 'title', 'lantai', 'ruangan', 'start', 'end']);
            return response()->json($data);
    	}
    	return view('user.jadwal');
    }

    public function status(Request $request){
        $data = Reservasi::where([
            ['reservationid', '!=', NULL]
        ])->where(function($query) use ($request){
            $query->where('fullname', 'LIKE', '%' . $request->term . '%');
        })->orderBy('reservationid', 'desc')->paginate(10);
        return view('user.status', ['reservasis'=>$data]);
    }
}
