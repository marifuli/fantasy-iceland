@extends('layouts.app')
@section('title')
Movie List 
@endsection
@section('content')
    <div class="container">
        <h4>
            Movies:
        </h4>
        <a href="{{route('admin.movies.create')}}" class="btn btn-info">
            <i class="fa fa-plus"></i> Create 
        </a>
        <hr>
        <div>
            @forelse ($data as $item)
                <div class="mt-3 row">
                    <div class="col-2">
                        <img src="/storage/{{ $item->image }}" class="w-100">
                    </div>
                    <div class="col">
                        <a class="d-inline-block mt-3">
                            <strong>
                                {{ $item->name }}
                            </strong> 
                        </a>
                        <br>
                        <br>
                        <a href="{{ route('admin.movies.edit', $item) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-edit"></i> Edit  
                        </a>
                        <form action="{{route('admin.movies.destroy', $item)}}" class="d-inline-block" method="POST">
                            <button class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete 
                            </button>
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @empty
                <i>Nothing found!</i>
            @endforelse
        </div>
    </div>
@endsection