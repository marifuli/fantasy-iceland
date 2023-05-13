@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            Register 
        </h3>
        <form method="POST" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Name:</label>
                <input type="text" id="form2Example1" class="form-control" 
                    name="name" value="{{ old('name') }}" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Phone number:</label>
                <input type="number" id="form2Example1" class="form-control" name="phone" value="{{ old('phone') }}" required/>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Email (optional):</label>
                <input type="email" id="form2Example1" class="form-control" name="email" value="{{ old('email') }}"/>
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example2">Password:</label>
                <input type="password" id="form2Example2" class="form-control" name="password" required/>
            </div>
            <!-- 2 column grid layout for inline styling -->
            {{-- <div class="row mb-4">
                <div class="col d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="form2Example34" checked />
                        <label class="form-check-label" for="form2Example34"> Remember me </label>
                    </div>
                </div>
                <div class="col">
                    <a href="#!">Forgot password?</a>
                </div>
            </div> --}}
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Register
            </button>
            <!-- Register buttons -->
            <div class="text-center">
                <p>Already a member? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </form>
    </div>
@endsection