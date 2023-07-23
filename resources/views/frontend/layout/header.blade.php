<section class="py-3">
    <div class="container">
        <div class="row mt-2">
            <div class="col-md-2">
                <a href="{{ route('index') }}">
                    {{-- <img class="fosuc_icon" src="{{ client()->logo ? asset(client()->logo) : logo() }}" alt="Focus-Icon">
                    --}}
                </a>
            </div>
            <div class="col-md-8 text-center">
                <div class="title">
                    @if (auth()->guard('client')->check())
                    <h3>
                        {{ client()->company?client()->company : client()->first_name.' '.client()->last_name }}
                    </h3>
                    @php
                    $client =
                    \App\Models\Client::with(['paylist'=>function($q){$q->where('is_expire',0)->where('status',1)->latest();}])->findOrFail(client()->id);
                    $payment = $client->paylist->first();
                    @endphp
                    <a href="{{ route('upgrade') }}">
                        @if ($payment)
                        You have left
                        {{(strtotime(optional($payment->expire_at)->format('Y-m-d')) -
                        strtotime(now()->format('Y-m-d')))/60/60/24}} Days
                        . Please buy/renew your license to continue...
                        @else
                        Please make a plan request by click here
                        @endif
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                @if (auth()->guard('client')->check())
                <form method="POST" action="{{ route('client.logout') }}">
                    @csrf
                    <input type="hidden" value="{{client()->id}}" name='client_id'>
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="width: 135px">SIGN OUT</button>
                </form>
                @impersonate()
                <a class="btn btn-sm btn-outline-info" href="{{ route('destroy.impersonate')}}">
                    Back to Admin Site
                </a>
                @endimpersonate
                @else
                <a class="btn btn-primary" href="{{route('login')}} ">Login</a>
                @endif
            </div>
        </div>
    </div>
</section>
