@extends('frontend.layout.master')
@section('title', 'Periodic BAS Cash Report')
@section('content')
    <?php $p = 'cbs';
    $mp = 'advr'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Periodic BAS Cash Report</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger" style="list-style: none">{{ $error }}</li>
                                    @endforeach
                                </ul>

                            @endif
                            <form action="{{ route('cbalance.report') }}" method="get" autocomplete="off">
                                <div class="row justify-content-center">
                                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                                    <div class="form-inline">
                                        <label class="mr-2 t_b">Select Business Activity: </label>
                                        <select class="form-control" onchange="location = this.value" name="profession_id">
                                            <option disabled selected value>Select Profession</option>
                                            @foreach ($client->professions as $profession)
                                            
                                                <option value="{{route('c.periodicCash.date',['profession' => $profession->id])}}">{{ $profession->name }}</option>
                                                {{-- <option value="{{ $profession->id }}">{{ $profession->name }}</option> --}}
                                            @endforeach
                                        </select>
                                    </div> &nbsp;&nbsp;&nbsp;&nbsp;
                                    {{-- <div class="form-inline">
                                        <input class="form-control btn btn-info" type="submit" value="Show Report">
                                    </div> --}}

                                    {{-- <select class="form-control" onchange="location = this.value">
                                        <option disabled selected value>Select Professions</option>
                                        @foreach($client->professions as $prof)
                                        <option
                                            {{$prof->id==$profession->id?'selected':''}}
                                            value="{{route('client_fixed_code.show',['client' => $client->id,'profession' => $prof->id])}}">
                                            {{$prof->name}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
