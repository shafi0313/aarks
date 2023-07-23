@extends('frontend.layout.master')
@section('title','Card List')
@section('content')
<?php $p="cl"; $mp="cf";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading">
                                <p>Employee List</p>
                            </div>

                            <table id="example" class="table table-bordered table-hover table-sm display">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th>Employee Name</th>
                                        <th>Phone Number</th>
                                        <th>Gender</th>
                                        <th>E-mail</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $x=1 @endphp
                                    @forelse ($employees as $emp)
                                    <tr>
                                        <td class="text-center">{{ $x++ }}</td>
                                        <td>{{$emp->fullname}} </td>
                                        <td>{{$emp->phone}} </td>
                                        <td>{{$emp->gender}} </td>
                                        <td>{{$emp->email}} </td>
                                        <td>{{$emp->state}} </td>
                                        <td>{{$emp->country}} </td>
                                        <td>
                                            <div class="text-center">
                                                <a href="{{route('employee.edit',$emp->id)}} " class="edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="{{route('employee.delete',$emp->id)}} " class="trash text-danger" onclick="return confirm('Are You sure?')">
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
