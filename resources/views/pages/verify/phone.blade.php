@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            Verify Phone 
            @if(env('APP_DEBUG'))
                {{session('code')}}
            @endif 
        </h3>
        <form method="POST" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($error ?? false)
                <div class="alert alert-danger mb-3">{{$error}}</div>
            @endif
            @if ($sent)
                <div class="alert alert-success mb-3">
                    Otp sent to this number. Please check your phone!
                </div>
            @endif
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Phone number:</label>
                <input type="text" id="form2Example1" class="form-control" 
                    readonly value="{{$user->phone}}"
                />
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example2">OTP code:</label>
                <input type="code" id="form2Example2" class="form-control" name="code"  required/>
            </div>
            @if (is_numeric($cache))
                <div class="send-again mb-3">

                </div>
                <script>
                    let time_passed = {{ $cache }}
                    let interval = setInterval(() => {
                        if(time_passed === (60 * 1))
                        {
                            $('.send-again').html(`<a class="btn btn-info" href="">Send again</a>`)
                            clearInterval(interval)
                        }
                        else 
                            $('.send-again').html(`Send OTP code again in ${(60 * 2) - time_passed}s`)
                        time_passed++ 
                    }, 1000);
                </script>
            @endif
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Verify 
            </button>
        </form>
    </div>
@endsection