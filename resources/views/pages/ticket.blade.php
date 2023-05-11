@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <img src="/storage/{{ $ticket->image }}" class="w-100">
            </div>
            <div class="col-sm-6">
                <h2>
                    {{ $ticket->name }}
                </h2>
                <p class="mt-3">
                    {{ $ticket->description }}
                </p>
                <b>
                    Price: {{$ticket->price}} Tk
                </b>
                <br>
                <br>
                <button class="btn btn-info" onclick="this.remove();$('.select-date').removeClass('d-none')">
                    Buy now 
                </button>
                <div class="d-none select-date mt-3">
                    <label for="">
                        Select Date: 
                    </label>
                    <div>
                        <input type="text" name="" class="date form-control" onchange="cahngedDate(this)">
                        @if(is_array($ticket->off_days_list) && count($ticket->off_days_list))
                            <small>
                                <i>
                                    Closed: 
                                    @foreach ($ticket->off_days_list as $item)
                                        {{\Carbon\Carbon::parse($item)->format('d F, Y')}},
                                    @endforeach
                                </i>
                            </small>
                        @endif 
                    </div>
                </div>
                <div class="d-none pay mt-3">
                    <a class="btn btn-info" _href="{{ route('ticket.buy', $ticket) }}">
                        Pay <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const OFF_DAYS = {!! is_array($ticket->off_days_list) && count($ticket->off_days_list) ? json_encode($ticket->off_days_list) : '[]' !!}
        contentLoaded(() => {
            window.picker = $('.date').datepicker({
                format: "dd-mm-yyyy"
            })
        })
        function cahngedDate(input) {
            let val = input.value 
            const mom = moment(val, 'DD-MM-YYYY')
            console.log(val);
            if(
                OFF_DAYS.includes(val) || mom.isBefore()
            )
            {
                $('.pay').addClass('d-none')
            }else 
            {
                $('.pay').removeClass('d-none')
                $('.pay a').attr(
                    'href', $('.pay a').attr('_href') + '?date=' + val.replaceAll('/', '-') 
                )
                $('.pay a')[0].focus()
            }
        }   
    </script>
@endsection