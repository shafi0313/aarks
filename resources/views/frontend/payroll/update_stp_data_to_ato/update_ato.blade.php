@extends('frontend.layout.master')
@section('title','Update STP Report')
@section('content')
<?php $p="upd"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading mb-3">
                            <h3>Update STP Report</h3>
                        </div>

                        <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                            <thead class="text-center" style="font-size: 14px">
                            <tr>
                                <th>Payment Date</th>
                                <th>Name</th>
                                <th>ABN</th>
                                <th>Timestampp</th>
                                <th>Gross Pay</th>
                                <th>Tax Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($atos as $ato)
                                <tr>
                                    <td>{{$ato->payment_date->format('d/m/Y')}} </td>
                                    <td>{{$ato->client->fullname??$ato->client->company}} </td>
                                    <td class="text-center">{{$ato->client->abn_number}} </td>
                                    <td class="text-center">{{$ato->created_at}} </td>
                                    <td class="text-right">$ {{number_format($ato->gross,2)}} </td>
                                    <td class="text-right">$ {{number_format($ato->payg,2)}} </td>
                                    <td>
                                        <div class="action">
                                            <a title="ATO Edit" href="{{route('SendDataAto.show',$ato->id)}}" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a> ||
                                            <form action="{{route('SendDataAto.delete',$ato->id)}}" style="
                                            display: inline;" method="POST">
                                                @method('delete')
                                                @csrf
                                                <button  title="ATO Deletew" type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
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
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
@stop
