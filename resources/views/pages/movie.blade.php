@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <img src="/storage/{{ $movie->image }}" class="w-100">
            </div>
            <div class="col-sm-6">
                <h2>
                    {{ $movie->name }}
                </h2>
                <p class="mt-3">
                    {{ $movie->description }}
                </p>
                <hr>
                <div class="mt-3">
                    Select Date & Time: 
                    <div class="mt-3">
                        @php
                            $period = new DatePeriod(
                                new DateTime($movie->start_at),
                                new DateInterval('P1D'),
                                new DateTime($movie->end_at)
                            );
                            $dates = [];
                            foreach ($period as $key => $value) {
                                $dates[] = $value->format('Y-m-d');       
                            }
                        @endphp
                        <select name="time_slot" class="form-control" onchange="getAvailableSeats()">
                            @foreach ($dates as $item)
                                @foreach ($movie->time_slots as $slot)
                                    @dump($slot)
                                    @php
                                        $time = \Carbon\Carbon::parse($item . ' ' . $slot . ':00');
                                    @endphp
                                    @if($time->isFuture())
                                        <option value="{{ $time->format('Y-m-d H:i:s') }}">
                                            {{ $time->format('d F, Y, h:i a') }}
                                        </option>
                                    @endif 
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    Select package: 
                    <div class="mt-3 packages">
                        @foreach (\App\Models\HallPackage::get() as $item)
                            <button class="btn" package="{{ $item->id }}" onclick="selectPackage(this)">
                                <b>
                                    {{ $item->name }}
                                </b>
                                <br>
                                {{ $item->price_in_cents / 100 }} Tk
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 seat-container"></div>
            </div>
        </div>
    </div>
    <script>
        const packages = {!! json_encode(\App\Models\HallPackage::get()->toArray()) !!};
        function selectPackage(btn) {
            let id = btn.getAttribute('package')
            $('.packages button').removeClass('btn-info')
            $(btn).addClass('btn-info')
            getAvailableSeats(() => {
                $('.seats').addClass('d-none')
                $('#seat' + id).removeClass('d-none')
            })
        }

        function getAvailableSeats(callback) {
            let id = "{{ $movie->id }}"
            let hall = $('.packages button.btn-info').attr('package')
            let time_slot = $('[name=time_slot]').val()
            if(!time_slot || !hall) return 

            let cont = $('.seat-container')
            cont.html('Loading...')
            $.get(`/get_movie_empty_seats/${id}/${time_slot}/${hall}`)
            .then(res => {
                document.querySelector('.seat-container').innerHTML = res 
                if(callback) callback()
            })
            .catch((err) => {
                cont.html('Failed to get seats, Try refrshingthe page')
            })
        }


        function selectSeat(btn) {
            if($(btn).hasClass('btn-info'))
                $(btn).removeClass('btn-info')
            else
                $(btn).addClass('btn-info')
            $('.main-pay').removeClass('d-none')
            $('.main-pay a').attr(
                'href', $('.main-pay a').attr('_href') + 
                    '?time_slot=' + $('select[name=time_slot]').val()
                    + '&package=' + $('.packages button.btn-info').attr('package')
                    + '&seat=' + (
                        [...document.querySelectorAll('.seats button.btn-info')].map(e => e.getAttribute('seat'))
                    )
            )
        }
    </script>
@endsection