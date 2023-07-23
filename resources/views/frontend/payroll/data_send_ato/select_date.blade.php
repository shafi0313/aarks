@extends('frontend.layout.master')
@section('title','SEND DATA TO STP')
@section('content')
<?php $p="sda"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div align="center" class="mb-4">
                            <strong style="font-size:30px;">SEND DATA TO STP</strong>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-2">
                                <strong style="color:green; font-size:20px;">Select Date: </strong>
                            </div>

                            <form action="{{route('SendDataAto.filter')}}" method="get" autocomplete="off" class="form-inline">
                                @csrf
                                <div class="form-group mx-1">
                                    <input class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                        name="date_from" placeholder="From Date">
                                </div>
                                <div class="form-group mx-1">
                                    <input class="form-control datepicker" data-date-format="dd/mm/yyyy" name="date_to"
                                        placeholder="To Date">
                                </div>
                                <div class="mx-3">
                                    <button type="submit" class="btn btn-success">Show Report</button>
                                </div>
                            </form>
                        </div>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->


<!-- inline scripts related to this page -->
<!-- Data Table -->
<script>
    $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
</script>
@stop
