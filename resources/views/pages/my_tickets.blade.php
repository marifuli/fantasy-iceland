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
                            <img src="/storage/{{ $item->ticket->image }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $item->ticket->name }}
                                </h5>
                                <a class="btn btn-sm btn-sucess mt-3" href="{{ route('ticket.download', $item->id) }}">
                                    <i class="fa fa-download"></i> Download ticket 
                                </a>
                            </div>
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
                                <a class="btn btn-sm btn-sucess" href="{{ route('movie.download', $item->id) }}" >
                                    <i class="fa fa-download"></i> Download ticket 
                                </a>
                            </div>
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