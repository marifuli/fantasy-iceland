@extends('layouts.app')
@section('content')
    <div class="container">
        <div>
            <h3>
                Tickets: 
            </h3>
            <div class="row">
                @forelse ($tickets as $item)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="card">
                            <img src="/storage/{{ $item->image }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $item->name }}
                                </h5>
                                <p class="card-text">
                                    {{ $item->description }}
                                </p>
                                <a type="button" href="{{route('ticket', $item)}}" class="btn btn-primary">
                                    View 
                                </a>
                            </div>
                        </div>    
                    </div>    
                @empty
                    <div class="col-6 col-sm-4 col-md-3">
                        <i>
                            No ticket found!
                        </i>
                    </div>
                @endforelse 
            </div>           
        </div>
        <div class="mt-4">
            <h3>
                Movies: 
            </h3>
            <div class="row">
                @forelse ($movies as $item)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="card">
                            <img src="/storage/{{ $item->image }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $item->name }}
                                </h5>
                                <p class="card-text">
                                    {{ $item->description }}
                                </p>
                                <button type="button" class="btn btn-primary">
                                    View 
                                </button>
                            </div>
                        </div>    
                    </div>    
                @empty
                    <div class="col-6 col-sm-4 col-md-3">
                        <i>
                            No ticket found!
                        </i>
                    </div>
                @endforelse 
            </div>           
        </div>
    </div>
@endsection