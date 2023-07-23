@extends('frontend.layout.master')
@section('title', 'Select Activity')
@section('content')
    <?php $p = 'cbr';
    $mp = 'acccounts'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Cash Book Report</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style="padding:10px;">
                                <label>Select Business Activity</label>
                                <select class="form-control" type="submit" onchange="location = this.value">
                                    <option disabled selected value>Select Profession</option>
                                    @foreach ($client->professions as $profession)
                                        <option value="{{ route('cashbook.reportOffice', [$client->id, $profession->id]) }}">
                                            {{ $profession->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
