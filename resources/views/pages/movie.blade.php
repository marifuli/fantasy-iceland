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
                        <select name="" class="form-control">
                            @foreach ($dates as $item)
                                @foreach ($movie->time_slots as $slot)
                                    <option value="">
                                        {{ \Carbon\Carbon::parse($item . ' ' . $slot)->format('d F, Y, h:i a') }}
                                    </option>
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
                @foreach (\App\Models\HallPackage::get() as $item)
                    <div class="d-none mt-3 seats" id="seat{{ $item->id }}">
                        Select Seat: 
                        <div class="mt-3">
                            @if (is_array($item->seats) && count($item->seats)) 
                                @foreach ($item->seats as $seat)
                                    <button class="btn" seat="{{ $seat }}" onclick="selectSeat(this)">
                                        <b>
                                            {{ $seat }}
                                        </b>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-3 main-pay d-none">
                            <a href="" class="btn btn-success">
                                Pay <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        const packages = {!! json_encode(\App\Models\HallPackage::get()->toArray()) !!};
        function selectPackage(btn) {
            let id = btn.getAttribute('package')
            $('.packages button').removeClass('btn-info')
            $(btn).addClass('btn-info')
            $('.seats').addClass('d-none')
            $('#seat' + id).removeClass('d-none')
        }
        function selectSeat(btn) {
            $('.seats button').removeClass('btn-info')
            $(btn).addClass('btn-info')
            $('.main-pay').removeClass('d-none')
        }
    </script>
@endsection