@extends('frontend.layout.master')
@section('title','Recurring List')
@section('content')
<?php $p="rl"; $mp="sales";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong style="color:green; font-size:20px;">Recurring List: </strong>
                            </div>
                            <div class="col-md-9">
                                <div class="row justify-content-end">
                                    <div class="col-3 form-group">
                                        <input class="form-control datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="From Date">
                                    </div>
                                    <div class="col-3 form-group">
                                        <input class="form-control datepicker" data-date-format="mm/dd/yyyy" name="" placeholder="To Date">
                                    </div>
                                    <div class="mr-3">
                                        <button class="btn btn-success" type="submit">Show Report</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr>
                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th >SL</th>
                                        <th >Date</th>
                                        <th class="no-sort">Customer Name</th>
                                        <th >Transection ID</th>
                                        <th >Recurring No</th>
                                        <th >Total Amount</th>
                                        <th  class="no-sort">Action</th>
                                        {{-- <th width="13%">Print/E-mail</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recurrings->groupBy('inv_no') as $i=>$recur)
                                    @php
                                        $recurring =$recur->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $i+1 }}</td>
                                        <td>{{$recurring->tran_date->format('d/m/Y')}} </td>
                                        <td>{{$recurring->customer->name}} </td>
                                        <td>{{$recurring->tran_id}} </td>
                                        <td>{{$recurring->inv_no}} </td>
                                        <td>$ {{number_format($recur->sum('amount'),2)}} </td>
                                        <td>
                                            <div class="action">
                                                <a  title="Rec. Item Edit" href="{{route('recurring_item.edit',$recurring->inv_no)}}" class="btn btn-info  ac_btn">
                                                    <i class="fas fa-edit"> </i>
                                                </a>
                                                <form action="{{route('recurring_item.destroy',$recurring->inv_no)}}" method="post" style="display: inline">
                                                    @csrf @method('delete')
                                                    <button  title="Rec. Item Delete" type="submit" class="btn  btn-danger ac_btn" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <button class="btn  btn-primary">Click</button>
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
@stop
