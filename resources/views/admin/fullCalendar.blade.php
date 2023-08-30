@extends('layouts.admin')
@section('content')
<style>
    .fc-event {
        width: auto;
        height: auto;
        display: flex;
        flex-wrap: wrap;
        align-content: center;
    }

</style>
<div class="relative overflow-x-auto h-screen bgSecondITS shadow-md sm:rounded-lg">
    <nav class="flex items-center justify-center flex-wrap p-5  w-full z-0 top-0  sm:justify-between">
		<div class="flex items-center flex-shrink-0 text-white  mr-6">
		<div>
			<span class="text-black font-bold no-underline hover:text-white hover:no-underline text-2xl pl-2"><i class="em em-grinning"></i>Jadwal</span>
		</div>
	</div>
    </nav>

    <div class="py-5 ml-9">
        <div class="flex flex-row justify-between w-56 ">
            <div>
                <label for="lantai" class="block  mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Lantai</label>
                <select id="lantai-dropdown" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-100 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Pilih Lantai</option>
                    @foreach ($lantai as $datas)
                    <option value="{{$datas->floornum}}">
                        {{$datas->floornum}}
                    </option>
                    @endforeach
            </select>
            </div>
        
            <div>
                <label for="kelas" class="block  mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
                <select id="kelas-dropdown" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-100 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Pilih Kelas</option>
            </select>
            </div>
        
        </div>
        
    {{-- <div class="inline-flex rounded-md shadow-sm" id="lantai">
        <a href="{{route('full-calendar')}}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
          Semua
        </a>
        <a href="{{route('lantaiSatu')}}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
          Lantai 4
        </a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
            Lantai 5
          </a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
            Lantai 6
        </a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
            Lantai 7
        </a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
            Lantai 8
        </a>
      </div> --}}
    </div>
    


    <div class="container bg-white  px-6 py-5 rounded-md z-0">
        <div id="calendar" class=""></div>
    </div>
    
</div>
   

<script>
        $(document).ready(function () {

             
        });
</script>

<script>

$(document).ready(function () {
    var schedule = @json($event);
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendar = $('#calendar').fullCalendar({
        editable:true,
        header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
        },
        // contentHeight:'auto',
        events:schedule,
        contentHeight:'auto',
        timeZone:'local',
        themeSystem: 'jquery-ui',
        selectable:true,
        selectHelper: true,
        select:function(start, end, allDay)
        {
            var title = prompt('Event Judul:');
            var lantai = prompt('Lantai Gedung:');
            var ruangan = prompt('Ruangan Event Berlangsung:');
            if(title && lantai && ruangan)
            {
                var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');
                var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');


                $.ajax({
                    url:"full-calendar/action",
                    type:"POST",
                    data:{
                        title: title,
                        lantai: lantai,
                        ruangan: ruangan,
                        start: start,
                        end: end,
                        type: 'add',
                        _token:'{{ csrf_token() }}'
                    },
                    success:function(data)
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Created Successfully");
                    }
                })
            }
        },
        editable:true,
        eventResize: function(event, delta)
        {
            var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
            var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
            var title = event.title;
            var lantai = event.lantai;
            var ruangan = event.ruangan;
            var id = event.id;
            $.ajax({
                url:"/full-calendar/action",
                type:"POST",
                data:{
                    title: title,
                    lantai: lantai,
                    ruangan: ruangan,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update',
                    _token:'{{ csrf_token() }}'
                },
                success:function(response)
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Updated Successfully");
                }
            })
        },
        eventDrop: function(event, delta)
        {
            var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
            var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
            var title = event.title;
            var lantai = event.lantai;
            var ruangan = event.ruangan;
            var id = event.id;
            $.ajax({
                url:"/full-calendar/action",
                type:"POST",
                data:{
                    title: title,
                    lantai: lantai,
                    ruangan: ruangan,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update',
                    _token:'{{ csrf_token() }}'
                },
                success:function(response)
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Updated Successfully");
                }
            })
        },

        eventClick:function(event)
        {
            if(confirm("Are you sure you want to remove it?"))
            {
                var id = event.id;
                $.ajax({
                    url:"/full-calendar/action",
                    type:"POST",
                    data:{
                        id:id,
                        type:"delete",
                        _token:'{{ csrf_token() }}'
                    },
                    success:function(response)
                    {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Deleted Successfully");
                    }
                })
            }
        }
    });
    $('.fc').css('background-color', 'white');
    $('#lantai-dropdown').on('change', function () {
                var floornum = this.value;
                $("#kelas-dropdown").html('');
                $.ajax({
                    url: "{{url('api/fetch-ruangan')}}",
                    type: "POST",
                    data: {
                        floornum: floornum,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#kelas-dropdown').html('<option value="">Pilih Kelas</option>');
                        $.each(result.ruangan, function (key, value) {
                            $("#kelas-dropdown").append('<option value="'+ value
                                .roomname +'">' + value.roomname + '</option>');
                        });
                    }
                });
            });

                $("#kelas-dropdown").on('change',function() {
            var selectedKelas = this.value
            alert("You have selected  " + selectedKelas);
            $.ajax({
                url:"{{ route('fetchcalendar') }}",
                type:"POST",
                data: {
                ruangan: selectedKelas,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success:function (result) {
                // alert(result);
                calendar.fullCalendar ('removeEvents'); 
                // console.log(result)
                schedule = result.event
                console.log(schedule)
                calendar.fullCalendar( 'addEventSource', schedule )
                calendar.fullCalendar("rerenderEvents");
            },
            error: function() {
                alert('Error occured');
            }
            
            });
            });
           
});
</script>
@endsection('content')