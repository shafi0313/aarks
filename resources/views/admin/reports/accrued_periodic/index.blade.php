@extends('admin.layout.master')
@section('title','Periodic GST/BAS(Accrued)')
@section('content')

	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs ace-save-state" id="breadcrumbs">
				<ul class="breadcrumb">
					<li>
						<i class="ace-icon fa fa-home home-icon"></i>
						<a href="{{ route('admin.dashboard') }}">Home</a>
					</li>
					<li>Reports</li>
					<li class="active">Periodic GST/BAS(Accrued)</li>
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
				<!-- Settings -->
					{{-- @include('admin.layout.settings') --}}
				<!-- /Settings -->

				<div class="row">
					<div class="col-xs-12">
						<!-- PAGE CONTENT BEGINS -->
						<div class="row">
							<div class="col-xs-12" >
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
											<th>Company /Trust/Partner ship Name</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email Address</th>
											<th>ABN Number</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@php $x=1; @endphp
										@foreach($clients as $client)
										<tr>
											<td class="center">{{$x++}}</td>
											<td>{{$client->company}}</td>
											<td>{{$client->first_name}}</td>
											<td>{{$client->last_name}}</td>
											<td>{{$client->email}}</td>
											<td>{{$client->abn_number}}</td>
											<td><div class="  action-buttons"> <a class="red" href="{{ route('accrued_periodic.profession',$client->id) }}">Select Client</a> </div></td>
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

</script>
@endsection
