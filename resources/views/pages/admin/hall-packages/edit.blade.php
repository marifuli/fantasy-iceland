@extends('layouts.app')
@section('title')
Update package
@endsection
@section('content')
    <div class="container">
        <h4>
            Update package:
        </h4>
        <hr>
        <form action="{{ route('admin.hall-packages.update', $package) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <div class="form-group mb-3">
                <label class="form-label" for="name">Name:</label>
                <input type="text" id="name" class="form-control" name="name" value="{{ $package->name }}"/>
            </div> 
            <div class="row">
                <div class="col-12">
                    <label for="">
                        Time slots: 
                    </label>
                    <div class="times max-height-500">
                        @if(is_array($package->seats) && count($package->seats))
                            @foreach ($package->seats as $item)
                                <div class="mt-2"> 
                                    <input type="text" placeholder="Seat No." name="seats[]" required value="{{ $item }}"> 
                                    <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
                                </div>
                            @endforeach
                        @else 
                            <div class="mt-2"> 
                                <input type="text" placeholder="Seat No." name="seats[]" required> 
                                <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
                            </div>
                        @endif 
                    </div>
                    <button onclick="event.preventDefault();add_time_picker();" class="mt-2 btn btn-sm">
                        <i class="fa fa-plus"></i> Add Seat  
                    </button>
                </div>
            </div>
            <div class="mb-3 mt-2">
                <label class="form-label">
                    Price ৳:
                </label>
                <input min="1" type="number" step="any" name="price" value="{{ $package->price_in_cents / 100 }}" class="form-control" required>
            </div>
            <button class="btn btn-info">
                Update 
            </button>
        </form>
    </div>
    <script>
        function add_time_picker() {
            $('.times').append(`
            <div  class="mt-2">
                <input type="text" placeholder="Seat No." name="seats[]" required> 
                <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
            </div>
            `)
        }
    </script>
@endsection