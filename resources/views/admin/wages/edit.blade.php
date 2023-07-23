@extends('admin.layout.master')
@section('title','Client')
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
                <li class="active">Edit Wages</li>
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

                    <form id="upWages" action=" {{route('stanwages.update',$wages->id)}} " method="post" novalidate>
                        @csrf @method('PUT')
                        <div class="col-md-12 showform">
                            <div class="widget-box">
                                <div class="widget-header">
                                    {{-- <h4 class="widget-title"><a href="">Back To
                                            Wages</a></h4> --}}

                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row" style="padding:20px;">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Wages Name<strong
                                                        style="color:red;">*</strong></label>
                                                <input type="text" class="form-control" name="name" id="wagesname"
                                                    value="{{$wages->name}}" required>
                                                <strong class="duplicat"></strong>
                                            </div>


                                            <div class="form-group">
                                                <label for="typeofwages">Type of Wages<strong style="color:red;">*</strong></label>
                                                <select class="form-control" name="type" id="typeofages">
                                                    <option value="Salary" {{$wages->type=='Salary'?'selected':''}} >Salary</option>
                                                    <option value="Hourly" {{$wages->type=='Hourly'?'selected':''}}>Hourly</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="link_group">Link Income Group<strong style="color:red;">*</strong></label>
                                                <select class="form-control" name="link_group" id="link_group">
                                                    <option {{$wages->link_group == 1?'selected':''}} value="1">Salary/Wages</option>
                                                    <option {{$wages->link_group == 2?'selected':''}} value="2">Overtime</option>
                                                    <option {{$wages->link_group == 3?'selected':''}} value="3">Bonus and Commission</option>
                                                    <option {{$wages->link_group == 4?'selected':''}} value="4">Allowance</option>
                                                    <option {{$wages->link_group == 5?'selected':''}} value="5">Director’s Fees </option>
                                                    <option {{$wages->link_group == 6?'selected':''}} value="6">Lump Sum Tuple</option>
                                                    <option {{$wages->link_group == 7?'selected':''}} value="7">ETP Tuple </option>
                                                    <option {{$wages->link_group == 8?'selected':''}} value="8">CDEP</option>
                                                    <option {{$wages->link_group == 9?'selected':''}} value="9">Salary Secriface</option>
                                                    <option {{$wages->link_group == 10?'selected':''}} value="10">Exempt Foreign Income</option>
                                                </select>
                                            </div>


                                            <div class="col-sm-4">

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="regularrate" id="regularrate" value="regular">
                                                        Regular Rate Multiplied by :
                                                    </label>
                                                </div>


                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="regular_rate"
                                                        id="regularrateval" value="{{$wages->regular_rate}}">
                                                </div>

                                            </div>


                                            <div class="col-sm-4">

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="regularrate" id="regularrate" value="fixed"
                                                            checked>
                                                        Fixed Hourly Rate of:
                                                    </label>
                                                </div>


                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="hourly_rate"
                                                        id="fixedhourlyval" value="{{$wages->hourly_rate}}">
                                                </div>

                                            </div>



                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="automatically" id="automatically" value="1">
                                                        Automatically Adjust Base Hourly or Base Salary Details
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info pull-right submit">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<script>
    $('#upWages input').on('change', (e)=> {
        var radiovalue = $('input[name=regularrate]:checked', '#upWages').val();
        if(radiovalue == 'regular'){
            $('#regularrateval').removeAttr('disabled', 'disabled');
            $('#fixedhourlyval').attr('disabled', 'disabled');
            $('#fixedhourlyval').val(0);
        } else{
            $('#regularrateval').val(0);
            $('#regularrateval').attr('disabled', 'disabled');
            $('#fixedhourlyval').removeAttr('disabled', 'disabled');
        }
    });
</script>
@endsection
