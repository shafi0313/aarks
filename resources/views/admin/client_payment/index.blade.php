@extends('admin.layout.master')
@section('title','Client Payment List')
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
				<li class="active">Client Payment List</li>
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
			<!-- Settings -->
			{{-- @include('admin.layout.settings') --}}
			<!-- /Settings -->
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                        <form action="" method="">
                            <div class="form-group">
                                <label style="color:green;">Select Activity</label>
                                <select class="form-control" onchange="location = this.value;">
                                    <option selected disabled>Select One Action</option>
                                    <option value="{{route('client_payment_list', ['type'=>'approve'])}}">Approved</option>
                                    <option value="{{route('client_payment_list', ['type'=>'pending'])}}">Pending</option>
                                    <option value="{{route('client_payment_list', ['type'=>'expired'])}}">Expired</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
@endsection
