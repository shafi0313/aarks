@extends('admin.layout.master')
@section('title', 'Cashbook Data Move')
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
                    <li>Trash/Move Data</li>
                    <li class="active">Cashbook Data Move</li>
                </ul><!-- /.breadcrumb -->

                {{-- <div class="nav-search" id="nav-search">
					<form class="form-search">
						<span class="input-icon">
							<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
							<i class="ace-icon fa fa-search nav-search-icon"></i>
						</span>
					</form>
				</div><!-- /.nav-search --> --}}
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-xs-12">
                                <br>
                                @php
                                    $cash = $cashbooks->first()
                                @endphp
                                <form action="{{ route('cashbook_data_move.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="client_id" value="{{ $cash->client_id }}">
                                    <input type="hidden" name="profession_id" value="{{ $cash->profession_id }}">
                                    <input type="hidden" name="tran_id" value="{{ $cash->tran_id }}">
                                    <div class="row" style="display:flex; justify-content: center;">
                                        <div class="col-md-3" >
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="" id="to_date" name="date"
                                                    class="form-control date-picker datepicker" id="datepicker"
                                                    data-date-format="dd/mm/yyyy" Placeholder="DD/MM/YYYY" required />
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="padding-top:20px;">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header" style="text-align: right;"> </div>
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>A/C Code</th>
                                                <th>A/C Name</th>
                                                <th>Narration</th>
                                                <th>Date</th>
                                                <th>Dr</th>
                                                <th>Cr</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $x=1; @endphp

                                            @foreach ($cashbooks as $cashbook)
                                                <tr>
                                                    <td class="center">{{ $cashbook->chart_id }}</td>
                                                    <td>{{ $cashbook->accountCode->name }}</td>
                                                    <td>{{ $cashbook->narration }}</td>
                                                    <td class="center">{{ bdDate($cashbook->tran_date) }}</td>
                                                    <td class="text-right">{{ nF2($cashbook->amount_debit) }}</td>
                                                    <td class="text-right">{{ nF2($cashbook->amount_credit) }}</td>
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
    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
@endsection
