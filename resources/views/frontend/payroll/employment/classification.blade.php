@extends('frontend.layout.master')
@section('title','Manage Wages')
@section('content')
<?php $p="ec"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">

                        <div class="card-body">
                            <div class="card-heading d-flex">
                                <p>Employment Classification Name</p>
                            </div>

                            <form action="{{ route('classification.store') }}" method="post">
                                @csrf
                            <div class="form-group">
                                <label class="t_b">Employment Classification Name: </label><strong class="t_red">*</strong>
                                <input class="form-control form-control-sm" type="text" name="name"><input type="hidden" name="client_id" value="{{client()->id}} ">
                            </div>
                            <div class="form-group">
                                <label for="" class="t_b">Classification Status: </label><strong class="t_red">*</strong>
                                <input class="form-control form-control-sm"  type="text" name="status" id="">
                            </div>

                            <button type="submit" class="btn btn-success float-right btn3">Save</button>
                            <br>
                            <br>
                            </form>



                            <table id="example" class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th>Employment Classification Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $x=1; @endphp
                                    @foreach ($ECS as $wage)
                                    <tr>
                                        <td class="text-center">{{ $x++ }}</td>
                                        <td>{{$wage->name}}</td>
                                        <td>{{$wage->status}}</td>
                                        <td style="text-align: center">
                                            <a href="{{route('classification.edit',$wage->id)}} " class="pemcil">
                                                <i class="fas fa-pencil-alt text-info"></i>
                                            </a> &nbsp;&nbsp;&nbsp;
                                            <a href="{{route('classification.delete',$wage->id)}} " class="trash" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>

@stop
