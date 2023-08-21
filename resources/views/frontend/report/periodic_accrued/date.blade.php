@extends('frontend.layout.master')
@section('title', 'Periodic BAS Accrued Report')
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
                            <h3>Periodic BAS Accrued Report</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger" style="list-style: none">{{ $error }}</li>
                                    @endforeach
                                </ul>

                            @endif
                            <form method="get" action="{{ route('c.periodicAccrued.report', $profession->id) }} ">
                                <input type="hidden" name="client_id" value="{{ client()->id }}" />
                                <input type="hidden" name="profession_id" value="{{ $profession->id }}" />
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <th>Period Date to Date</th>
                                    </tr>
                                    @foreach ($periods as $period)
                                        <tr>
                                            <td>
                                                {{ $period->first()->year }} => 
                                                @foreach ($period as $per)
                                                    <div class="form-check form-check-inline px-1">
                                                        <label>
                                                            <input type="checkbox" name="peroid_id[]"
                                                                value="{{ $per->id }} ">{{ $per->start_date->format(aarks('frontend_date_format')) }}
                                                            To
                                                            {{ $per->end_date->format(aarks('frontend_date_format')) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <button class="btn btn-info form-control" type="submit">Period
                                                Report</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
