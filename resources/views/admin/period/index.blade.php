@extends('admin.layout.master')
@section('title', 'Period List')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('select_method') }}">Add/Edit Entry</a>
                    </li>
                    <li class="bCColor">
                        {{ clientName($client) }}
                    </li>
                    <li class="bPColor">{{ $profession->name }}</li>
                    <li class="active">Add Period</li>
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
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header">
                                    @if (empty($client->company))
                                        {{ $client->first_name . ' ' . $client->last_name }}
                                    @else
                                        {{ $client->company }}
                                    @endif

                                    @can('admin.period.create')
                                        <button class="btn btn-danger" data-toggle="collapse" data-target="#demo"
                                            style="float: right !important;line-height: 16px;">
                                            <span>
                                                <i class="fa fa-plus"></i>
                                                Add Period
                                            </span>
                                        </button>
                                    @endcan
                                </div>
                                @if (!empty($errors->any()))
                                    <small class="text-danger">* {{ $errors->first() }}</small>
                                @endif
                                <div id="demo" class="collapse" style="margin: 30px">
                                    <form class="form-inline" action="{{ route('period.store') }}" method="POST"
                                        style="margin: 0 auto;">
                                        {{ csrf_field() }}
                                        <div class="row d-flex">
                                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                                            <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Select Financial Year <strong
                                                            style="color:red;">*</strong></label>
                                                    <input id="financial-year" type="number" name="year"
                                                        class="form-control" length="4" placeholder="Year"
                                                        value="{{ old('year') }}"><br>
                                                    <small id="msg" style="display: none;color: red">HEllo</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="usr">Start Month <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="" class="form-control datepicker"
                                                        value="{{ old('start_date') }}" name="start_date"
                                                        placeholder="Start Time" id="datepicker" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="usr">End Month <strong
                                                            style="color:red;">*</strong></label>
                                                    <input type="" class="form-control datepicker"
                                                        value="{{ old('end_date') }}" name="end_date"
                                                        placeholder="End Time" id="pwd" autocomplete="off">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    <table id="period-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Financial Year</th>
                                                <th>Period From Date</th>
                                                <th>Period to Date</th>
                                                @canany(['admin.period.index'])
                                                    <th class="center" width="80px">Action</th>
                                                @endcanany
                                                <th class="center" width="160px">Add Data</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($periods as $period)
                                                <tr>
                                                    <td>{{ $period->year }}</td>
                                                    <td>{{ $period->start_date->format(aarks('frontend_date_format')) }}
                                                    </td>
                                                    <td>{{ $period->end_date->format(aarks('frontend_date_format')) }}</td>
                                                    @canany(['admin.period.delete'])
                                                        <td class="center">
                                                            <a class="_delete"
                                                                data-route="{{ route('period.destroy', $period->id) }}"
                                                                title="Delete"><i
                                                                    class="ace-icon fa fa-trash-o bigger-130 red"></i></a>
                                                        </td>
                                                    @endcanany
                                                    <td class="center">
                                                        <a class=""
                                                            href="{{ route('edit_period', [$profession->id, $period->id, $client->id]) }}">Add/Edit
                                                            Data</a>
                                                        {{-- <button class="btn btn-success"><span style="color: black">Add/Edit Data</span></button> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Modal -->


    <script>
        jQuery(function($) {
            var myTable =
                $('#period-table')
                .DataTable({
                    bAutoWidth: false,
                    "aoColumns": [{
                            "bSortable": false
                        },
                        null, null, null, null, null,
                        {
                            "bSortable": false
                        }
                    ],
                    "aaSorting": [],
                    select: {
                        style: 'multi'
                    }
                });
        });
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });

        $('#financial-year').keyup(function() {
            let year = $(this).val();
            if (year.length == 4) {
                $('#msg').show().html('Your selected financial year july ' + (year - 1) + ' to June ' + year);
            } else {
                $('#msg').hide();
                //$('#msg').show().html('Must be 4 Digit');
            }

        });
    </script>
@stop
