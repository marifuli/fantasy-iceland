@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            Verify Tickets 
        </h3>
        <form method="POST" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            @if(isset($err))
                <div class="alert alert-danger mb-3">{{$err}}</div>
            @endif 
            <!-- Email input -->
            <div class="form-outline mb-4">
                <input type="text" id="form2Example1" class="form-control"
                    value="{{ $emailOrPhone ?? old('emailOrPhone') }}" name="emailOrPhone"
                    @if(isset($emailOrPhone)) disabled @endif 
                    />
                <label class="form-label" for="form2Example1">Email or Phone number</label>
            </div>
            <!-- Password input -->
            @if(isset($emailOrPhone))
                <div class="form-outline mb-4">
                    <input type="text" id="form2Example2" class="form-control" name="check_otp" required />
                    <label class="form-label" for="form2Example2">
                        OTP code:
                    </label>
                </div>
            @endif 
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">
                @if(isset($emailOrPhone))
                Verify otp 
                @else 
                Send Otp  
                @endif 
            </button>
        </form>
    </div>
@endsection