@extends('layouts.app')
@section('title')
Edit Ticket
@endsection
@section('content')
    <div class="container">
        <h4>
            Edit Ticket:
        </h4>
        <hr>
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <div class="form-group mb-3">
                <label class="form-label" for="name">Name:</label>
                <input type="text" id="name" class="form-control" name="name" value="{{ $ticket->name }}"/>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="form12">
                    Description:
                </label>
                <textarea class="form-control" name="description" required>{{ $ticket->description }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label" for="image1">
                    Thumbnail image:
                </label>
                <input type="file" accept="image/*" class="form-control" name="image" id="image1" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="image2">
                    Base Ticket image:
                </label>
                <input type="file" accept="image/*" class="form-control" id="image2" name="base_ticket_image" />
            </div>
            <div class="mb-3">
                <label for="">
                    Closed days: 
                </label>
                <input type="text" class="form-control date" name="off_days_list" value="{{ $ticket->off_days_list ? join(',', $ticket->off_days_list) : '' }}">
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Price ৳:
                </label>
                <input type="number" step="any" name="price" value="{{ $ticket->price_in_cents / 100}}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Discounted Price (optional) ৳:
                </label>
                <input type="number" step="any" name="discount_price" value="{{ $ticket->discount_price ? $ticket->discount_price / 100 : 0 }}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="form12">
                    Users can't buy after this days: 
                </label>
                <input type="number" class="form-control" name="cant_buy_after_days" value="{{ $ticket->cant_buy_after_days }}"/>
            </div>
            <button class="btn btn-info">
                Update 
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