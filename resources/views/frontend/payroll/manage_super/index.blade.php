@extends('frontend.layout.master')
@section('title','Manage Superannuation')
@section('content')
<?php $p="ms"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading d-flex">
                            <p>Superannuation</p>
                            <button type="button" class="btn adddata ml-auto m_c_b" data-toggle="modal"
                                data-target="#clientSuperModal">
                                <i class="ace-icon fa fa-plus"></i> Add Super Category
                            </button>

                        </div>
                        <!-- Modal -->

                        <div class="modal fade" id="clientSuperModal" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title" align='left'>Add New Superannuation</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            align='right'>&times;</button>
                                    </div>
                                    <form role="form" action=" {{route('clientannuation.store')}} " method="POST"
                                        autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="client_id"
                                            value="{{client()->id}} ">
                                        <div class="modal-body">
                                            <div class="row" style="padding-left:33px">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label for="exampleInputEmail1"
                                                            class=" control-label text-right">Superannuation
                                                            Name<strong style="color:red;">*</strong></label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-control-sm"
                                                                name="name" id="name" required>
                                                            <strong class="duplicat"></strong>
                                                            @error('name')<span class="text-danger">{{$message}}
                                                            </span>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="e_rate" class="col-sm-6 control-label"
                                                            align="right">Equals</label>
                                                        <div class="col-sm-6">
                                                            <input type="number" class="form-control form-control-sm"
                                                                id="e_rate" name="e_rate">
                                                            @error('e_rate')<span class="text-danger">{{$message}}
                                                            </span>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="e_tools" class="col-sm-4 control-label"
                                                            align="right">Percent of</label>
                                                        <div class="col-sm-6">
                                                            <select name="e_tools" id="e_tools"
                                                                class="form-control form-control-sm">
                                                                <option value="BS" selected>Basic Sallary</option>
                                                                <option value="GH">Gross Hours</option>
                                                            </select>
                                                            @error('e_tools')<span class="text-danger">{{$message}}
                                                            </span>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="e_fix_amt" class="col-sm-6 control-label"
                                                            align="right">Equals</label>
                                                        <div class="col-sm-6">
                                                            <input type="number" class="form-control form-control-sm"
                                                                id="e_fix_amt" name="e_fix_amt">
                                                            @error('e_fix_amt')<span class="text-danger">{{$message}}
                                                            </span>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <div class="form-group row">
                                                        <label for="e_period" class="col-sm-4 control-label"
                                                            align="right">Dollars Per</label>
                                                        <div class="col-sm-6">
                                                            <select name="e_period" id="e_period"
                                                                class="form-control form-control-sm">
                                                                <option value="Pay Period" selected>Pay Period</option>
                                                                <option value="Per Hours">Per Hours</option>
                                                                <option value="Per Month">Per Month</option>
                                                                <option value="Per Forthnighty">Per Forthnighty</option>
                                                            </select>
                                                            @error('e_period')<span class="text-danger">{{$message}}
                                                            </span>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-left: 7px">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label for="e_excl_amt" class="control-label">Exclusions:
                                                            Exclude
                                                            the first</label>
                                                        <div class="col-sm-5">
                                                            <input type="number" class="form-control form-control-sm"
                                                                id="e_excl_amt" name="e_excl_amt">
                                                            @error('e_excl_amt')<span class="text-danger">{{$message}}
                                                            </span>@enderror

                                                        </div>
                                                        <label for="inputEmail3" class="control-label" align="left">of
                                                            eligible wages
                                                            from</label>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="t_rate" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control form-control-sm" id="t_rate" name="t_rate">
                                    @error('t_rate')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="t_tools" class="col-sm-4 control-label" align="right">Percent
                                        Per</label>
                                    <div class="col-sm-6">
                                        <select name="t_tools" id="t_tools" class="form-control form-control-sm">
                                            <option value="BS" selected>Basic Sallary</option>
                                            <option value="GH">Gross Hours</option>
                                        </select>
                                        @error('t_tools')<span class="text-danger">{{$message}} </span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="t_fix_amt" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control form-control-sm" id="t_fix_amt" name="t_fix_amt">
                                    @error('t_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="t_period" class="col-sm-4 control-label" align="right">Dollars Per</label>
                    <div class="col-sm-6">
                        <select name="t_period" id="t_period" class="form-control form-control-sm">
                            <option value="Pay Period" selected>Pay Period</option>
                            <option value="Per Hours">Per Hours</option>
                            <option value="Per Month">Per Month</option>
                            <option value="Per Forthnighty">Per Forthnighty</option>
                        </select>
                        @error('t_period')<span class="text-danger">{{$message}} </span>@enderror
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="t_excl_amt" class="col-sm-6 control-label" align="right">Threshold:
                                    Calculate once eligible wages of
                                    paid</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-controlvform-control-sm" id="t_excl_amt" name="t_excl_amt">
                                    @error('t_excl_amt')<span class="text-danger">{{$message}} </span>@enderror
    </div>
    <label for="inputEmail3" class="col-sm-3 control-label" align="left">of eligible wages
        from</label>
    </div>

    </div>
    </div> --}}
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Submit</button>
    </div>
    </form>
    </div>
    </div>
    </div>
    <table id="example" class="table table-bordered table-hover table-sm display">
        <thead class="text-center" style="font-size: 14px">
            <tr>
                <th width="4%">SL</th>
                <th>Superannuation Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $x=1 @endphp
            @foreach ($supperAnnuations as $sup_anu)
            <tr>
                <td class="text-center">{{ $x++ }}</td>
                <td> {{$sup_anu->name}} </td>
                <td></td>
            </tr>
            @endforeach
            @foreach ($clientAnnuations as $client_anu)
            <tr>
                <td class="text-center">{{ $x++ }}</td>
                <td> {{$client_anu->name}} </td>
                <td>
                    <div class="action">
                        <a href=" {{route('clientannuation.edit',$client_anu->id)}} " class="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href=" {{route('clientannuation.delete',$client_anu->id)}} " class="trash"
                            onclick="return confirm('Are You Sure?')">
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


















<!-- Footer Start -->

<!-- Footer End -->

<!-- inline scripts related to this page -->
<script>
    $("#e_rate").on('keyup', e => {
        let e_rate = $("#e_rate").val();
        if (e_rate.length >= 1) {
            $('#e_fix_amt').attr('disabled', 'disabled');
        } else {
            $('#e_fix_amt').removeAttr('disabled', 'disabled');
        }
    });
    $("#e_fix_amt").on('keyup', e => {
        let e_rate = $("#e_fix_amt").val();
        if (e_rate.length >= 1) {
            $('#e_rate').attr('disabled', 'disabled');
        } else {
            $('#e_rate').removeAttr('disabled', 'disabled');
        }
    });

    $("#t_rate").on('keyup', e => {
        let e_rate = $("#t_rate").val();
        if (e_rate.length >= 1) {
            $('#t_fix_amt').attr('disabled', 'disabled');
        } else {
            $('#t_fix_amt').removeAttr('disabled', 'disabled');
        }
    });
    $("#t_fix_amt").on('keyup', e => {
        let e_rate = $("#t_fix_amt").val();
        if (e_rate.length >= 1) {
            $('#t_rate').attr('disabled', 'disabled');
        } else {
            $('#t_rate').removeAttr('disabled', 'disabled');
        }
    });
    $(document).ready(function () {
        $('#example').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    });
</script>

@stop
