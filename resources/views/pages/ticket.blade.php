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
            </div>
        </div>
    </div>
@endsection