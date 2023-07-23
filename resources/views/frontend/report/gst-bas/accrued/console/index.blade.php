@extends('frontend.layout.master')
@section('title', 'Consolidation Accrued GST/BAS Report')
@section('content')
<?php $p="cbs"; $mp="advr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading">
                        <h3> GST/BAS Consolidation Accrued Select Date</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger" style="list-style: none">{{$error}}</li>
                            @endforeach
                        </ul>
                        @endif
                        <form action="{{route('client_accrued_basis.report')}}" method="get" autocomplete="off">
                            <input type="hidden" name="client_id" value="{{$client->id}}" />
                            <div class="row justify-content-center">
                                <div class="col-lg-3" style="padding-top:20px;">
                                    <div class="form-group">
                                        <label>Form Date</label>
                                        <input type="" id="from_date" name="from_date" class="form-control date-picker datepicker" id="datepicker"
                                            data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                                    </div>
                                </div>
                                <div class="col-lg-3" style="padding-top:20px;">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="" id="to_date" name="to_date" class="form-control date-picker datepicker" id="datepicker"
                                            data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" />
                                    </div>
                                </div>
                                <div class="col-lg-3" style="padding-top:40px;">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" >Show Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
