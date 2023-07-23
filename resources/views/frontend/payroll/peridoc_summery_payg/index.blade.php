@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="pep"; $mp="payroll";?>
<style>
    label {
        display: inline-block;
        margin-bottom: 0px;
    }
    table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
    border-bottom-width: 0;

    padding: 2px 5px;
    margin: 0;
    font-size: 14px;
}
</style>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <strong style="color:green; font-size:20px;">Payslip List: </strong>
                            </div>


                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-6 text-right">
                                        <form action="{{route('payslip.print')}}" method="post" class="payIdClass">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Print</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form action="" method="post" class="payIdClass">
                                            @csrf
                                            <input type="hidden" name="payslip_id[]" class="payslip_id">
                                            <button type="submit" class="btn btn-info">Send Mail</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-header mt-3">
                            <p>Payslip</p>
                        </div>
                        <table id="example" class="table table-bordered table-hover display table-sm table-striped">
                            <thead class="text-center" style="font-size: 13px;">
                                <tr>
                                    <th>
                                        <input id="check" type="checkbox" class="allcheck mr-1">
                                        <label for="check" class="checkbox-inline">All Check Employee</label>
                                    </th>
                                    <th>Payment Date</th>
                                    <th style="text-align:right;">Translation ID</th>
                                    <th style="text-align:right;">Gross Pay</th>
                                    <th style="text-align:right;">Tax Amount</th>
                                    <th style="text-align:right;">Deduction</th>
                                    <th style="text-align:right;">Net Pay</th>
                                    <th>Pay Cycle</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ()
                                <tr>
                                    <td>&nbsp;
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="check_id" class="check_id"
                                                value="" class="allcheck">

                                        </label>
                                    </td>
                                    <td class="text-center"></td>
                                    <td>

                                    </td>
                                    <td class="text-right"></td>

                                    <td style="text-align: center;">
                                        <a href="" class="">Update for STP
                                            {{-- <i class="far fa-edit"></i> --}}
                                        </a>&nbsp;
                                        <form action="" method="post" style="display: inline">
                                            @csrf
                                            @method('delete')
                                            <button onclick="return confirm('Are you sure')" title="Delete" style="border: none; color:red" type="submit" >
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">
                                        <h1 class="display-1 text-danger text-center">EMPTY</h1>
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
<!-- Data Table -->
<script>
    $(".allcheck").click(e=>{
        if ($(".allcheck").is(':checked')) {
            $('input:checkbox').prop("checked", true);
            $("input:checkbox[name=check_id]:checked").each(function(){
                $('.payIdClass').append('<input type="hidden" name="payslip_id[]" value="'+$(this).val()+'"class="paySlip'+$(this).val()+'" />');
            });
        } else {
            $('input:checkbox').prop("checked", false);
        }
    });
    $(".check_id").click(function(e){
        if ($(".check_id").is(':checked')) {
            $("input:checkbox[name=check_id]:checked").each(function(){
                $('.payIdClass').append('<input type="hidden" name="payslip_id[]" value="'+$(this).val()+'" class="paySlip'+$(this).val()+'"/>');
            });
        }else{
            $(".paySlip"+$(this).val()).detach();
        }
    });
    $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
</script>
@stop
