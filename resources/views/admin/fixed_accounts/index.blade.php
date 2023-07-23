@extends('admin.layout.master')
@section('title','Fixed Accounts')
@section('content')

	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs ace-save-state" id="breadcrumbs">
				<ul class="breadcrumb">
					<li>
						<i class="ace-icon fa fa-home home-icon"></i>
						<a href="{{ route('admin.dashboard') }}">Home</a>
					</li>
					<li>Tools</li>
					<li class="active">Fixed Accounts</li>
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
							<div class="col-xs-12">
								<div class="clearfix">
									<div class="pull-right tableTools-container"></div>
								</div>
								<div class="table-header" style="text-align: right;">

								</div>

								<!-- div.table-responsive -->

								<!-- div.dataTables_borderWrap -->

								<table id="dynamic-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th class="center">SN</th>
											<th>Company /Trust/Partner ship Name</th>
											<th>First Name</th>
											<th>Middle Name</th>
											<th>Last Name</th>
											<th>Email Address</th>
											<th>Add Data Fixed</th>
											<td>Add Data Tran</td>
											<th>Payment Transation ID set</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="center">1</td>
											<td></td>
											<td>Mohamed</td>
											<td></td>
											<td>Shieta</td>
											<td>saumya.ranasinghe@gmail.com</td>
											<td></td>
											<td>
												<a href="">Set Transaction Id</a>
											</td>
											<td>
												<a class="grenn" href="">Payment Transation Id Set</a>
											</td>
											<td>
												<div class="  action-buttons">
													<a class="red" href="">Invoice Transation Id Set</a>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
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
