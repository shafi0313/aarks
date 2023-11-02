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
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header" style="text-align: right;"> </div>
                                <!-- div.table-responsive -->
                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center">SN</th>
                                                <th>Date</th>
                                                <th>Transaction id</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $x=1; @endphp

                                            @foreach ($cashbooks as $cashbook)
                                            @php
                                                $cashbook = $cashbook->first()
                                            @endphp
                                                <tr>
                                                    <td class="center">{{ $x++ }}</td>
                                                    <td class="center">{{ $cashbook->tran_id }}</td>
                                                    <td class="center">{{ bdDate($cashbook->tran_date) }}</td>

                                                    <td class="text-center">
                                                        <div class="  action-buttons">
                                                            <a class="red"
                                                                href="{{ route('cashbook_data_move.show', [$cashbook->client_id, $cashbook->profession_id, $cashbook->tran_id]) }}">Show</a>
                                                        </div>
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
    <script></script>
@endsection
