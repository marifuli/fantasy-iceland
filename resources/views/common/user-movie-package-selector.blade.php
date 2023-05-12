
@foreach (\App\Models\HallPackage::get() as $item)
    <div class="d-none mt-3 seats" id="seat{{ $item->id }}">
        Select Seat: 
        <div class="mt-3">
            @if (is_array($item->seats) && count($item->seats)) 
                @php
                    $isEmpty = true;
                @endphp
                @foreach ($item->seats as $seat)
                    @if(
                        !\App\Models\MovieTicket::is_booked(
                            $ticket->id, $time_slot, $package->id, $seat
                        )
                    )
                        @php
                            $isEmpty = false;
                        @endphp
                        <button class="btn" seat="{{ $seat }}" onclick="selectSeat(this)">
                            <b>
                                {{ $seat }}
                            </b>
                        </button>
                    @endif 
                @endforeach
                @if ($isEmpty)
                    <i>
                        All seats are booked in this package. Try another one!
                    </i>
                @endif
            @endif
        </div>
        <div class="mt-3 main-pay d-none">
            <a href="" _href="{{ route('movie.buy', $ticket) }}" class="btn btn-success">
                Pay <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endforeach