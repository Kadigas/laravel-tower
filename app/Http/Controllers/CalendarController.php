<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ruangan;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
		$datas['lantai'] = Ruangan::distinct('floornum')->get(["floornum"]);
		// dd($datas);


    	$events = array();
        $schedule = Event::all();
        foreach($schedule as $schedules){
            $events[] =[
				'id' => $schedules->id,
                'title' => $schedules->title,
                'lantai' => $schedules->lantai,
                'ruangan' => $schedules->ruangan,
                'start' => $schedules->start,
                'end' => $schedules->end,
                
            ];
        }

    	return view('admin.fullCalendar',$datas,['event' => $events]);
    }


	public function fetchruangan(Request $request)
    {
        $datas['ruangan'] = Ruangan::where("floornum", $request->floornum)
                                ->get(["roomname"]);
  
        return response()->json($datas);
    }

	public function fetchcalendar(Request $request)
    {
        $data = Event::where("ruangan", $request->ruangan)->get();
		$events  = array();
		foreach($data as $schedules){
            $events[] =[
				'id' => $schedules->id,
                'title' => $schedules->title,
                'lantai' => $schedules->lantai,
                'ruangan' => $schedules->ruangan,
                'start' => $schedules->start,
                'end' => $schedules->end,
                
            ];
        }
  
        return response()->json(['event'=>$events]);
    }

	public function lantaiSatu(Request $request) 
	{
    	if($request->ajax())
    	{
    		$data = Event::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
					   ->where('lantai', '=', 1)
                       ->get(['id', 'title', 'lantai', 'ruangan', 'start', 'end']);
            return response()->json($data);
    	}
    	return view('admin.fullCalendar');
    }

    public function action(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
    			$event = Event::create([
    				'title'		=> $request->title,
					'lantai'	=> $request->lantai,
					'ruangan'	=> $request->ruangan,
    				'start'		=> $request->start,
    				'end'		=> $request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = Event::find($request->id)->update([
    				'title'		=>	$request->title,
					'lantai'	=> $request->lantai,
					'ruangan'	=> $request->ruangan,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = Event::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
