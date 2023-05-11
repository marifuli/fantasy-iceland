@extends('layouts.app')
@section('title')
Hall packages
@endsection
@section('content')
    <div class="container">
        <h4>
            Hall packages:
        </h4>
        <a href="{{route('admin.hall-packages.create')}}" class="btn btn-info">
            <i class="fa fa-plus"></i> Create 
        </a>
        <hr>
        <div>
            @forelse ($data as $item)
                <div class="mt-3 row">
                    <div class="col">
                        <a class="d-inline-block mt-3">
                            <strong>
                                {{ $item->name }}
                            </strong> 
                        </a>
                        <br>
                        <br>
                        <a href="{{ route('admin.hall-packages.edit', $item) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-edit"></i> Edit  
                        </a>
                        <form action="{{route('admin.hall-packages.destroy', $item)}}" class="d-inline-block" method="POST">
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