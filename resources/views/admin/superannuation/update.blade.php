@extends('admin.layout.master')
@section('title','Superannuation')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>

                <li>Admin</li>
                <li>Manage Payroll</li>
                <li class="active">Edit Superannuation</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>

                    <!-- div.table-responsive -->

                    <!-- div.dataTables_borderWrap -->

                    <!-- Modal -->

                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Superannuation</h4>
                    </div>
                    <form role="form" action=" {{route('superannuation.update',$superannuation->id)}} " method="POST" autocomplete="off">
                        @csrf @method('put')
                        <div class="modal-body">
                            <div class="row" style="padding:20px;">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Superannuation Name<strong
                                            style="color:red;">*</strong></label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{$superannuation->name}}">
                                    <strong class="duplicat"></strong>
                                    @error('name')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="e_rate" class="col-sm-3 control-label" align="right">Equals</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="e_rate" name="e_rate" value="{{$superannuation->e_rate}}">
                                            @error('e_rate')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="e_tools" class="col-sm-2 control-label" align="right">Percent
                                            of</label>
                                        <div class="col-sm-3">
                                            <select name="e_tools" id="e_tools" class="form-control">
                                                <option value="BS" {{$superannuation->e_tools == 'BS'?'selected':''}} >Basic Sallary</option>
                                                <option value="GH" {{$superannuation->e_tools == 'GH'?'selected':''}} >Gross Hours</option>
                                            </select>
                                            @error('e_tools')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="e_fix_amt" class="col-sm-3 control-label"
                                            align="right">Equals</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="e_fix_amt" name="e_fix_amt" value="{{$superannuation->e_fix_amt}}">
                                            @error('e_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="e_period" class="col-sm-2 control-label" align="right">Dollars
                                            Per</label>
                                        <div class="col-sm-3">
                                            <select name="e_period" id="e_period" class="form-control">
                                                <option {{$superannuation->e_period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay Period</option>
                                                {{-- <option {{$superannuation->e_period == 'Per Hours'?'selected':''}} value="Per Hours">Per Hours</option>
                                                <option {{$superannuation->e_period == 'Per Month'?'selected':''}} value="Per Month">Per Month</option>
                                                <option {{$superannuation->e_period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">Per Forthnighty --}}
                                                </option>
                                            </select>
                                            @error('e_period')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="e_excl_amt" class="col-sm-3 control-label" align="right">Exclusions:
                                            Exclude the first</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="e_excl_amt" name="e_excl_amt" value="{{$superannuation->e_excl_amt}}">
                                            @error('e_excl_amt')<span class="text-danger">{{$message}} </span>@enderror

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label" align="left">of eligible
                                            wages from</label>

                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="t_rate" class="col-sm-3 control-label" align="right">Equals</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="t_rate" name="t_rate" value="{{$superannuation->t_rate}}">
                                            @error('t_rate')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_tools" class="col-sm-2 control-label" align="right">Percent
                                            Per</label>
                                        <div class="col-sm-3">
                                            <select name="t_tools" id="t_tools" class="form-control">
                                                <option value="BS" {{$superannuation->t_tools == 'BS'?'selected':''}}>Basic Sallary</option>
                                                <option value="GH" {{$superannuation->t_tools == 'GH'?'selected':''}}>Gross Hours</option>
                                            </select>
                                            @error('t_tools')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="t_fix_amt" class="col-sm-3 control-label"
                                            align="right">Equals</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="t_fix_amt" name="t_fix_amt" value="{{$superannuation->t_fix_amt}}">
                                            @error('t_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_period" class="col-sm-2 control-label" align="right">Dollars
                                            Per</label>
                                        <div class="col-sm-3">
                                            <select name="t_period" id="t_period" class="form-control">
                                                <option {{$superannuation->t_period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay Period</option>
                                                <option {{$superannuation->t_period == 'Per Hours'?'selected':''}} value="Per Hours">Per Hours</option>
                                                <option {{$superannuation->t_period == 'Per Month'?'selected':''}} value="Per Month">Per Month</option>
                                                <option {{$superannuation->t_period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">Per Forthnighty</option>
                                            </select>
                                            @error('t_period')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="t_excl_amt" class="col-sm-6 control-label" align="right">Threshold:
                                            Calculate once eligible wages of
                                            paid</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="t_excl_amt" name="t_excl_amt" value="{{$superannuation->t_excl_amt}}">
                                            @error('t_excl_amt')<span class="text-danger">{{$message}} </span>@enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label" align="left">of eligible
                                            wages from</label>

                                    </div>
                                </div> --}}
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->




<!-- inline scripts related to this page -->
<script type="text/javascript">
$("#e_rate").on('keyup',e=>{
    let e_rate = $("#e_rate").val();
    if(e_rate.length >= 1){
        $('#e_fix_amt').attr('disabled', 'disabled');
        $('#e_fix_amt').val(0);
    }else{
        $('#e_fix_amt').removeAttr('disabled', 'disabled');
    }
});
$("#e_fix_amt").on('keyup',e=>{
    let e_rate = $("#e_fix_amt").val();
    if(e_rate.length >= 1){
        $('#e_rate').attr('disabled', 'disabled');
        $('#e_rate').val(0);
    }else{
        $('#e_rate').removeAttr('disabled', 'disabled');
    }
});

$("#t_rate").on('keyup',e=>{
    let e_rate = $("#t_rate").val();
    if(e_rate.length >= 1){
        $('#t_fix_amt').attr('disabled', 'disabled');
        $('#t_fix_amt').val(0);
    }else{
        $('#t_fix_amt').removeAttr('disabled', 'disabled');
    }
});
$("#t_fix_amt").on('keyup',e=>{
    let e_rate = $("#t_fix_amt").val();
    if(e_rate.length >= 1){
        $('#t_rate').attr('disabled', 'disabled');
        $('#t_rate').val(0);
    }else{
        $('#t_rate').removeAttr('disabled', 'disabled');
    }
});

</script>




@endsection
