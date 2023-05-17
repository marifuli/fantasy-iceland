@extends('layouts.app')
@section('content') 
    <div class="container mt-5">
        <h3 class="text-center mb-4">
            @dump(session('forgot_pass'))
            @if(session('forgot_pass'))
                Forgot password 
            @else 
                Verify 
                Tickets 
            @endif 
            @if(env('APP_DEBUG'))
                {{session('code')}}
            @endif 
        </h3>
        <form method="POST" action="{{ route('verify-tickets') }}" style="width: 90%; margin: auto; max-width: 400px">
            @csrf
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-3">{{$error}}</div>
                @endforeach
            @endif
            @if(isset($err))
                <div class="alert alert-danger mb-3">{{$err}}</div>
            @endif 
            @if ($sent ?? false)
                <div class="alert alert-success mb-3">
                    Otp sent to this number. Please check your phone!
                </div>
            @endif
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Email or Phone number:</label>
                <input type="text" id="form2Example1" class="form-control"
                    value="{{ $emailOrPhone ?? '' }}" name="emailOrPhone"
                    @if(isset($emailOrPhone)) disabled @endif 
                    />
            </div>
            <!-- Password input -->
            @if(isset($emailOrPhone))
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example2">
                        OTP code:
                    </label>
                    <input type="text" id="form2Example2" class="form-control" name="check_otp" required />
                </div>
                @if (is_numeric($cache))
                    <div class="send-again mb-3">

                    </div>
                    <script>
                        let time_passed = {{ $cache }}
                        let interval = setInterval(() => {
                            if(time_passed === (60 * 2))
                            {
                                $('.send-again').html(`<a class="btn btn-info" onclick="$('.__form form').submit()">Send again</a>`)
                                clearInterval(interval)
                            }
                            else 
                                $('.send-again').html(`Send OTP code again in ${(60 * 2) - time_passed}s`)
                            time_passed++ 
                        }, 1000);
                    </script>
                @endif
            @endif 

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">
                @if(isset($emailOrPhone))
                    @if(session('forgot_pass'))
                        Verify 
                    @else 
                        Verify otp 
                    @endif 
                @else 
                    Send Otp  
                @endif 
            </button>
        </form>
    </div>

    @if (is_numeric($cache))
        <div class="d-none __form">
            <form method="POST" action="{{ route('verify-tickets') }}" style="width: 90%; margin: auto; max-width: 400px">
                @csrf
                <input type="hidden" id="form2Example1" class="form-control"
                value="{{ $emailOrPhone ?? '' }}" name="emailOrPhone"
                @if(isset($emailOrPhone)) disabled @endif 
                />
            </form>
        </div>
    @endif 
@endsection