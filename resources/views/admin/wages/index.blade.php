@extends('admin.layout.master')
@section('title','Wages')
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
                <li class="active">Wages</li>
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
                    <div class="table-header" style="text-align: right;">
                        <button type="button" class="btn btn-success adddata" data-toggle="modal" data-target="#addWages"><i
                                class="ace-icon fa fa-plus"></i> Add Wages Category </button>
                    </div>

                    <!-- div.table-responsive -->

                    <!-- div.dataTables_borderWrap -->

                    <!-- Modal -->

                    <div class="modal fade" id="addWages" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add New Wages Category</h4>
                                </div>
                                <form id="wagesFrom" role="form" action='{{route('stanwages.store')}} ' method="POST" autocomplete="off" novalidate>
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="wageName">Wages Name<strong style="color:red;">*</strong></label>
                                                <input type="text" class="form-control" id="wageName" value="{{old('name')}}" name="name">
                                                @error('name')
                                                    <span class="text-danger">{{$message}} </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="type">Type of Wages<strong style="color:red;">*</strong></label>
                                                <select class="form-control" value="{{old('type')}}" name="type" id="type">
                                                    <option value="Salary" selected>Salary</option>
                                                    <option value="Hourly">Hourly</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="link_group">Link Income Group<strong style="color:red;">*</strong></label>
                                                <select class="form-control" name="link_group" id="link_group">
                                                    <option value="1" selected>Salary/Wages</option>
                                                    <option value="2">Overtime</option>
                                                    <option value="3">Bonus and Commission</option>
                                                    <option value="4">Allowance</option>
                                                    <option value="5">Director’s Fees </option>
                                                    <option value="6">Lump Sum Tuple</option>
                                                    <option value="7">ETP Tuple </option>
                                                    <option value="8">CDEP</option>
                                                    <option value="9">Salary Secriface</option>
                                                    <option value="10">Exempt Foreign Income</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="regularrate" id="regular_rate" value="regular">
                                                        Regular Rate Multiplied by:
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input class="form-control form-control-sm" type="number" value="{{old('regular_rate')}}" name="regular_rate" id="regularrateval">
                                                </div>
                                                @error('regular_rate')
                                                <span class="text-danger">{{$message}} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="regularrate" id="hourly_rate" value="fixed">
                                                        Fixed Hourly Rate of:
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input class="form-control form-control-sm" type="number" value="{{old('hourly_rate')}}" name="hourly_rate" id="fixedhourlyval">
                                                @error('hourly_rate')
                                                <span class="text-danger">{{$message}} </span>
                                                @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="automatically" id="automatically" value="1"> Automatically Adjust Base Hourly
                                                        or Base Salary Details
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-seccess">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End add Wages --}}
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center">SN</th>
                                    <th>Wages Name</th>
                                    <th>Type of Wages</th>
                                    <th>Linked Account</th>
                                    <th>Regular Rate</th>
                                    <th>Fixed Hourly Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($wages as $i=>$wage)
                                <tr>
                                    <td class="center">{{$i+1}}</td>
                                    <td>{{$wage->name}}</td>
                                    <td>{{$wage->type}}</td>
                                    <td>
                                        @switch($wage->link_group)
                                            @case(1) Salary/Wages @break
                                            @case(2) Overtime @break
                                            @case(3) Bonus and Commission @break
                                            @case(4) Allowance @break
                                            @case(5) Director’s Fees @break
                                            @case(6) Lump Sum Tuple @break
                                            @case(7) ETP Tuple @break
                                            @case(8) CDEP @break
                                            @case(9) Salary Secriface @break
                                            @case(10) Exempt Foreign Income @break
                                            @default NOTHING
                                        @endswitch
                                    </td>
                                    <td>{{number_format($wage->regular_rate,4)}}</td>
                                    <td>{{number_format($wage->hourly_rate,4)}}</td>
                                    <td>
                                        <div class="  action-buttons">
                                            <a title="Standard Wages Edit" class="green" href="{{route('stanwages.edit',$wage->id)}}">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>

                                            <a title="Standard Wages Delete" class="red" href="{{route('stanwages.delete',$wage->id)}}">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="7">
                                        <h1 class="display-2 text-danger">EMPTY TABLE DATA</h1>
                                    </td>
                                </tr>

                                @endforelse
                                <tr>
                                    <td colspan="7">
                                        <div class="span text-center">{{$wages->links()}} </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script>
    $('#wagesFrom input').on('change', (e)=> {
        var radiovalue = $('input[name=regularrate]:checked', '#wagesFrom').val();
        if(radiovalue == 'regular'){
            $('#regularrateval').removeAttr('disabled', 'disabled');
            $('#fixedhourlyval').attr('disabled', 'disabled');
        } else{
            $('#regularrateval').attr('disabled', 'disabled');
            $('#fixedhourlyval').removeAttr('disabled', 'disabled');
        }
    });
</script>
@endsection
