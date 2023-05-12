@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            Verify Phone 
        </h3>
        <form method="POST" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($error ?? false)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
            @endif
            <!-- Email input -->
            <div class="form-outline mb-4">
                <input type="text" id="form2Example1" class="form-control" 
                    readonly value="{{$user->phone}}"
                />
                <label class="form-label" for="form2Example1">Phone number</label>
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <input type="code" id="form2Example2" class="form-control" name="code"  required/>
                <label class="form-label" for="form2Example2">OTP code</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Verify 
            </button>
        </form>
    </div>
@endsection