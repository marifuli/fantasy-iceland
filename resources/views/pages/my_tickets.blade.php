@extends('layouts.app')
@section('content')
    <div class="container mt-3">
        <h3 class="mb-4">
            My Tickets 
        </h3>
        <hr>
        <div>
            <h4>
                Park Tickets:
            </h4>
            <div class="row">
                @forelse ($tickets as $item)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="card">
                            @if ($item->ticket)
                                <img src="/storage/{{ $item->ticket->image }}" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $item->ticket->name }}
                                    </h5>
                                    @if($item->ticket->base_ticket_image)
                                        <a class="btn btn-sm btn-sucess mt-3" href="{{ route('ticket.download', $item->id) }}"
                                            download="Entry ticket.{{ @explode('.', $item->ticket->base_ticket_image)[1] }}"
                                        >
                                            <i class="fa fa-download"></i> Download ticket 
                                        </a>
                                    @endif 
                                </div>
                            @else 
                                <div class="text-danger p-3"><i>Ticket not available</i></div>
                            @endif
                        </div>    
                    </div>
                @empty
                    <i>
                        Nothing Found!
                    </i>
                @endforelse
            </div>
        </div>
        <div class="mt-4">
            <h4>
                Movie Tickets:
            </h4>
            <div class="row">
                @forelse ($movies as $item)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="card">
                            @if($item->movie)
                                <img src="/storage/{{ $item->movie->image }}" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $item->movie->name }} 
                                    </h5>
                                    <p>
                                        <small>
                                            Seat: {{ $item->seat_no }}
                                        </small>
                                    </p>
                                    @if($item->movie->base_ticket_image)
                                        <a class="btn btn-sm btn-sucess" href="{{ route('movie.download', $item->id) }}" 
                                            download="{{ $item->movie->name }} ticket.{{ @explode('.', $item->movie->base_ticket_image)[1] }}"
                                        >
                                            <i class="fa fa-download"></i> Download ticket 
                                        </a>
                                    @endif 
                                </div>
                            @else 
                                <div class="text-danger p-3"><i>Ticket not available</i></div>
                            @endif
                        </div>
                    </div>
                @empty
                    <i>
                        Nothing Found!
                    </i>
                @endforelse
            </div>
        </div>
    </div>
@endsection