<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ruangan;
use App\Models\Rooms;
use Carbon\Carbon;

class RuanganController extends Controller
{
    public function index() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all();
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }

    public function lantai4() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 4);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 4)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }

    public function lantai5() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 5);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 5)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }

    public function lantai6() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 6);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 6)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }

    public function lantai7() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 7);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 7)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }

    public function lantai8() {
        $now = Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ruangans = Ruangan::all()->where('floornum', '=', 8);
        $events = Event::query()
        ->where('start', '<=', $now)
        ->where('end', '>=', $now)
        ->where('lantai', '=', 8)
        ->get(['ruangan', 'lantai']);

        return view('admin.ruanganIndex', compact('ruangans', 'events'));
    }
    

    public function detailReservasi($id) {
        $data = Rooms::where('id','=',$id)->first();
        return view('modal.ruangan1', ['rooms'=>$data]);
    }
}