@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <h1>Activate Your Account - Burn Your BTC</h1>
                    <p>
                        Hello {{ $user->email }}, your account is currently inactive.
                    </p>
                    <p>
                        Send <strong>{{ rtrim(rtrim(number_format($burn_req / 100000000, 8), "0"), ".") }}</strong> BTC to the "burn address" below to complete account activation.
                    </p>
                    <h3 class="text-success">{{ $burn_address }}</h3>
                    <hr>
                    <p>
                        Click the button below once the required amount has reached 1 or more network confirmations.
                    </p>
                    <p>
                        <a class="btn btn-lg btn-success" href="{{ route('account.burn.check') }}">Verify BTC has been Burned</a>
                    </p>
                    <p>
                        Burned: {{ rtrim(rtrim(number_format($btc_burned / 100000000, 8), "0"), ".") }} BTC
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
