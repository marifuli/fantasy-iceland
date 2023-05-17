@extends('layouts.app')
@section('content') 
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            Reset Password 
        </h3>
        <form method="POST" action="{{ route('reset.password.submit', session('code')) }}" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">
                    New password:
                </label>
                <input type="password" id="form2Example1" class="form-control" required
                    value="{{ old('password') }}" name="password"
                    />
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">
                    Confirm password:
                </label>
                <input type="password" id="form2Example1" class="form-control" required
                    value="{{ old('password_confirmation') }}" name="password_confirmation"
                    />
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Reset 
            </button>
        </form>
    </div> 
@endsection