@extends('admin.layout.master')
@section('title','Leave')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Admin</li>
                <li>Manage Payroll</li>
                <li class="active">Edit Deducation</li>
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
                                <form role="form" action="{{route('deducation.update',$deducation->id)}}" method="POST" autocomplete="off" id="deductionUpdate">
                                    @csrf @method('put')
                                    <div class="modal-body">
                                        <div class="row" style="padding:0  20px 10px 0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="col-sm-3 control-label text-right">
                                                        Deducation Name<strong style="color:red;">*</strong></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="name" id="name" value="{{$deducation->name}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0  20px 10px 0">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rate" class="col-sm-6 control-label"
                                                        align="right">Equals</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="rate"
                                                            name="rate"value="{{$deducation->rate}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tools" class="col-sm-4 control-label"
                                                        align="right">Percent of</label>
                                                    <div class="col-sm-6">
                                                        <select name="tools" id="tools" class="form-control">
                                                            <option value="BS" {{$deducation->tools == 'BS'?'selected':''}}>Basic Sallary</option>
                                                            <option value="GH" {{$deducation->tools == 'GH'?'selected':''}}>Gross Hours</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0  20px 10px 0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fix_amt" class="col-sm-6 control-label"
                                                        align="right">Equals</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control" id="fix_amt" name="fix_amt" value="{{$deducation->fix_amt}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="period" class="col-sm-4 control-label"
                                                        align="right">Dollars Per</label>
                                                    <div class="col-sm-6">
                                            <select name="period" id="period" class="form-control">
                                                <option {{$deducation->period == 'Pay Period'?'selected':''}} value="Pay Period" selected>Pay Period</option>
                                                <option {{$deducation->period == 'Per Hours'?'selected':''}} value="Per Hours">Per Hours</option>
                                                <option {{$deducation->period == 'Per Month'?'selected':''}} value="Per Month">Per Month</option>
                                                <option {{$deducation->period == 'Per Forthnighty'?'selected':''}} value="Per Forthnighty">Per Forthnighty
                                                </option>
                                            </select>
                                            @error('period')<span class="text-danger">{{$message}} </span>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:0  20px 10px 50px">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="limit" id="nolimit" value="1" {{$deducation->limit == 1?'checked':''}} > No Limit
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="limit" id="limit" value="2" {{$deducation->limit == 2?'checked':''}} > Until Date
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </div>
                                </form>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

{{-- <script>
$('#deductionUpdate input').on('change', (e)=> {
    var limit = $('input[name=limit]:checked', '#deductionUpdate').val();
    if(limit == 2){
    $('#limit').removeAttr('disabled', 'disabled');
    $('#nolimit').attr('disabled', 'disabled');
    } else{
    $('#limit').attr('disabled', 'disabled');
    $('#nolimit').removeAttr('disabled', 'disabled');
    }
});
</script> --}}
@endsection
