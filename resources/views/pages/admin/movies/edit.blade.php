@extends('layouts.app')
@section('title')
Edit movie
@endsection
@section('content')
    <div class="container">
        <h4>
            Edit movie:
        </h4>
        <hr>
        <form action="{{ route('admin.movies.update', $movie) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <div class="form-group mb-3">
                <label class="form-label" for="name">Name:</label>
                <input type="text" id="name" class="form-control" name="name" value="{{ $movie->name }}"/>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="form12">
                    Description:
                </label>
                <textarea class="form-control" name="description" required>{{ $movie->description }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label" for="image1">
                    Thumbnail image:
                </label>
                <input type="file" accept="image/*" class="form-control" name="image" id="image1"/>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="">
                            Start from: 
                        </label>
                        <input type="date" class="form-control" name="start_at" value="{{ @explode(' ', $movie->start_at)[0] }}" required>
                    </div>        
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="">
                            Will end at: 
                        </label>
                        <input type="date" class="form-control" name="end_at" value="{{ @explode(' ', $movie->end_at)[0] }}" required>
                    </div>       
                </div>
                <div class="col-12">
                    <label for="">
                        Time slots: 
                    </label>
                    <div class="times">
                        @if (is_array($movie->time_slots) && count($movie->time_slots))
                            @foreach ($movie->time_slots as $item)
                                <div class="mt-2"> 
                                    <input type="time" name="time_slots[]" value="{{ 
                                        $item
                                    }}" required> <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
                                </div>
                            @endforeach
                        @else 
                            <div class="mt-2"> 
                                <input type="time" name="time_slots[]" required> <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
                            </div>
                        @endif
                    </div>
                    <button onclick="event.preventDefault();add_time_picker();" class="mt-2 btn btn-sm">
                        <i class="fa fa-plus"></i> Add slot 
                    </button>
                </div>
            </div>
            <button class="btn btn-info mt-3">
                Update  
            </button>
        </form>
    </div>
    <script>
        function add_time_picker() {
            $('.times').append(`
            <div  class="mt-2">
                <input type="time" name="time_slots[]" required> <i onclick="this.parentElement.remove()" class="fa fa-times text-danger"></i>
            </div>
            `)
        }
    </script>
@endsection