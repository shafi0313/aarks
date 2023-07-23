@extends('frontend.layout.master')
@section('title','Manage Deduction')
@section('content')
<?php $p="md"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading d-flex">
                            <p>Deduction</p>
                            <button type="button" class="btn m_c_b ml-auto" data-toggle="modal"
                                data-target="#addDeducsModal">
                                Add Deduction Category
                            </button>
                        </div>

                        <table id="example" class="table table-bordered table-hover table-sm display">
                            <thead class="text-center" style="font-size: 14px">
                                <tr>
                                    <th width="4%">SL</th>
                                    <th>Deduction Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $x=1 @endphp
                                @foreach ($stanDeducs as $stnDeduc)
                                <tr>
                                    <td class="text-center">{{ $x++ }}</td>
                                    <td> {{$stnDeduc->name}} </td>
                                    <td></td>
                                </tr>

                                @endforeach
                                @foreach ($deducs as $deduc)
                                <tr>
                                    <td class="text-center">{{ $x++ }}</td>
                                    <td> {{$deduc->name}} </td>
                                    <td>
                                        <div class="action">
                                            <a href="{{route('clientdeduction.edit',$deduc->id)}} " class="edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{route('clientdeduction.delete',$deduc->id)}} " class="trash"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
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

<!-- Modal -->
<div class="modal fade" id="addDeducsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header card-heading text-light">
                <h5 class="modal-title  " id="exampleModalLabel">Add New Deducation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form role="form" id="deductionForm" action="{{route('clientdeduction.store')}} " method="POST">
                            @csrf
                            <input type="hidden" name="client_id" value="{{client()->id}} ">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="" class="control-label">Deduction
                                                Name<strong style="color:red;">*</strong></label>
                                            <div class="col-10">
                                                <input type="text" class="form-control form-control-sm" name="name" id="name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group row ">
                                            <label for="rate" class="col-sm-4 control-label text-right">Equals</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control form-control-sm" id="rate" name="rate">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group row ">
                                            <label for="tools" class="col-sm-4 control-label text-right">Percent of</label>
                                            <div class="col-sm-8">
                                                <select name="tools" id="tools" class="form-control form-control-sm">
                                                    <option value="BS" selected="">Basic Sallary</option>
                                                    <option value="GH">Gross Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="fix_amt" class="col-sm-4 control-label text-right">Equals</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control form-control-sm" id="fix_amt" name="fix_amt">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label for="period" class="col-sm-4 control-label text-right">Dollars Per</label>
                                            <div class="col-sm-8">
                                                <select name="period" id="period" class="form-control form-control-sm">
                                                    <option value="Pay Period" selected="">Pay Period</option>
                                                    {{-- <option value="Per Hours">Per Hours</option>
                                            <option value="Per Month">Per Month</option>
                                            <option value="Per Forthnighty">Per Forthnighty</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-4" style="padding:0  20px 10px 50px">
                                    {{-- <div class="col-2"></div> --}}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="limit" id="nolimit" value="1"> No Limit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="limit" id="limit" value="2"> Until Date
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer Start -->

    <!-- Footer End -->

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        });
    </script>

    @stop
