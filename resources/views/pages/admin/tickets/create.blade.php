@extends('layouts.app')
@section('title')
Create Ticket
@endsection
@section('content')
    <div class="container">
        <h4>
            Create Ticket:
        </h4>
        <hr>
        <form action="{{ route('admin.tickets.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <div class="form-group mb-3">
                <label class="form-label" for="name">Name:</label>
                <input type="text" id="name" class="form-control" name="name" value="{{old('name')}}"/>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="form12">
                    Description:
                </label>
                <textarea class="form-control" name="description" required>{{old('description')}}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label" for="image1">
                    Thumbnail image:
                </label>
                <input type="file" accept="image/*" class="form-control" name="image" id="image1" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="image2">
                    Base Ticket image:
                </label>
                <input type="file" accept="image/*" class="form-control" id="image2" name="base_ticket_image" required/>
            </div>
            <div class="mb-3">
                <label for="">
                    Closed days: 
                </label>
                <input type="text" class="form-control date" name="off_days_list" value="{{old('off_days_list')}}" placeholder="Pick the multiple dates">
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Price ৳:
                </label>
                <input type="number" step="any" name="price" value="{{old('price')}}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Discounted Price (optional) ৳:
                </label>
                <input type="number" step="any" name="discount_price" value="{{old('discount_price')}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="form12">
                    Users can't buy after this days: 
                </label>
                <input type="number" class="form-control" name="cant_buy_after_days" value="{{old('cant_buy_after_days') ?? 10}}"/>
            </div>
            <button class="btn btn-info">
                Create
            </button>
        </form>
    </div>
    <script>
        contentLoaded(() => {
            $('.date').datepicker({
                multidate: true,
                format: 'dd-mm-yyyy'
            });
        })
    </script>
@endsection