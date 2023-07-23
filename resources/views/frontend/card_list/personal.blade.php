@extends('frontend.layout.master')
@section('title','Card List')
@section('content')
<?php $p="cl"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Name</th>
                                <th>Cust Ref</th>
                                <th>Card Type</th>
                                <th>Phone Number</th>
                                <th>Balance</th>
                                <th>A.B.N</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1 ;@endphp
                            @forelse ($customers as $customer)
                            <tr>
                            <td class="text-center">{{$i++}}</td>
                                <td>{{$customer->customer_ref}}</td>
                                <td>{{$customer->customer_type}}</td>
                                <td>{{$customer->phone}}</td>
                                <td>{{$customer->email}}</td>
                                <td>{{$customer->abn}}</td>
                                <td>
                                    <div class="text-center">
                                        <a href="" class="edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href=" text-danger" class="trash">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            @empty
<tr>
    <td colspan="8" align="center">
        <h1 class="display-1 text-danger">Table Empty</h1>
    </td>
</tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>

@stop
