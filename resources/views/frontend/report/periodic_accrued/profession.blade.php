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
                            <div class="row justify-content-center">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <div class="form-inline">
                                    <label class="mr-2 t_b">Select Business Activity: </label>
                                    <select class="form-control" onchange="location = this.value" name="profession_id">
                                        <option disabled selected value>Select Profession</option>
                                        @foreach ($client->professions as $profession)
                                            <option
                                                value="{{ route('c.periodicAccrued.date', ['profession' => $profession->id]) }}">
                                                {{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
