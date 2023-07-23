@extends('admin.layout.master')
@section('title','Fuel Tax Credit')
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
					<li>Rules</li>
					<li class="active">Fuel Tax Credit</li>
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
								<div class="table-header" style="text-align: right;">
									<a href="#" class="btn btn-success adddata">
										<i class="ace-icon fa fa-plus"></i> Add New Fuel Tax Credit</a>
								</div>

								{{-- <div class="table-header" style="text-align: right;">
									<a href="{{route('FuelTaxCredit.create')}}" class="btn btn-success">
										<i class="ace-icon fa fa-plus"></i> Add New Fuel Tax Credit</a>
								</div> --}}

								<!-- div.table-responsive -->

								<!-- div.dataTables_borderWrap -->

								<form action="" method="post">
									<div class="col-md-12 showform" style="display:none;">
										<div class="widget-box">
											<div class="widget-header">
												<h4 class="widget-title">Add New Fuel Tax Credit</h4>
											</div>

											<input type="hidden" name="id" id="id">
											<div class="widget-body">
												<div class="widget-main">
													<div class="row">
													<form action="index_submit" method="get" >
														<div class="col-md-10">
															@csrf
								                            <div class="form-group col-md-3">
								                                <label>Financial Year <span style="color:red;">*</span></label>
								                                <input type="number" class="form-control" name="financial_year" required>
								                                <div id="mgs"></div>
								                            </div>


								                            <div class="form-group col-md-3">
								                                <label>Start Month <span style="color:red;">*</span></label>
								                                <input required class="form-control" name="start_date" type="date" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YY">
								                            </div>

								                            <div class="form-group col-md-3">
								                                <label>End Month <span style="color:red;">*</span></label>
								                                <input required class="form-control" name="end_date" type="date" data-date-format="dd/mm/yyyy"  placeholder="DD/MM/YY">
								                            </div>

								                            <div class="form-group col-md-3">
								                                <label>Rate<span style="color:red;">*</span></label>
								                                <input type="text" class="form-control" name="rate">
								                            </div>

														</div>


														<div class="col-md-2" style="padding-top:20px;">

														<div class="form-group">
															<button type="submit" class="btn btn-info update dateculcattion">Save Fuel Tax Credit</button>
														</div>
														</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div><!-- /.span -->
								</form>

								<table id="dynamic-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td>Financial Year</td>
											<td>Start Month</td>
											<td>End Month</td>
											<td>Rate</td>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

									@foreach ($fuel_tax_credits as $data)
										<tr>
											<td>{{$data->financial_year}}</td>
											<td>{{ \Carbon\Carbon::parse($data->start_date)->format('d/m/Y')}}</td>
											<td>{{ \Carbon\Carbon::parse($data->end_date)->format('d/m/Y')}}</td>
											<td>{{$data->rate}}</td>
											<td>
												<div class="  action-buttons">
													<a title="Fuel Tax Edit" class="green" href="{{ route('FuelTaxCredit.edit', $data->id)}}">
                                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                    </a>




													<form action="{{route('FuelTaxCredit.destroy',$data->id)}}" style="display: initial;" method="POST" onsubmit="return confirm('Are You Sure?')">
														@csrf
														@method('DELETE')
														<button title="Fuel Tax LTR Delete" type="submit" class="red delete">
														<i class="ace-icon fa fa-trash-o bigger-130"></i>
														</button>
													</form>


													{{-- <a class="red delete" href="">
														<i class="ace-icon fa fa-trash-o bigger-130"></i>
													</a> --}}
												</div>
											</td>
										</tr>
									@endforeach
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





	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		$(".dateculcattion").on('click', function(){
				var financialyear = $("#financialyear").val();
				var finalyear = financialyear-1;

					var startmmonth   = $("#startmmonth").val();
					var endmonth      = $("#endmonth").val();
					var arr = startmmonth.split('/');
					var day = arr[0];
					var mon = arr[1];
					var year = arr[2];

				if(year !== ""){
						if(year >= finalyear && year <= financialyear){

							if(year == finalyear){
								if(mon >= 7){

									if(endmonth !== ""){
										var array = endmonth.split('/');
										var eday = array[0];
										var emon = array[1];
										var eyear = array[2];

										if(financialyear >= eyear && finalyear <= eyear){

											if(eyear > finalyear){

												if(emon <= 6){
													} else {

														alert("Month is wrong");
														return false;
													}

											} else {

												if(emon >= 7){

													if(mon <= emon){

														} else {
														alert("Month is wrong");
														return false;
														}

													} else {
														alert("Month is wrong");
														return false;
													}
											}
										} else {
										alert("End year is wrong");
										return false;
										}
									}
								} else {
									alert("Start month is wrong!");
									return false;
								}
							} else {
									if(mon <= 7){

									if(endmonth !== ""){
										var array = endmonth.split('/');
										var eday = array[0];
										var emon = array[1];
										var eyear = array[2];

										if(financialyear >= eyear && finalyear <= eyear){

											if(eyear > finalyear){

												if(emon <= 6){
													} else {

														alert("Month is wrong");
														return false;
													}

											} else {

												if(emon >= 7){

													if(mon <= emon){

														} else {
														alert("Month is wrong");
														return false;
														}



													} else {
														alert("Month is wrong");
														return false;
													}
											}
										} else {
										alert("End year is wrong");
										return false;
										}
									}
								} else {
									alert("Start month is wrong!");
									return false;
								}
							}
						} else {

							alert("Start Year is wrong");
							return false;

						}
				}else {
					alert("Start Date can not be empty");
					return false;
				}
			});

			$("#financialyear").on('keyup', function(e){
			var financialyear = $(this).val();
			var newyear = financialyear;
			var oldyear = financialyear-1;
			var oldcn = "your seleceted financial year july ";
			var count = " to June ";
			var res = oldcn.concat(oldyear);
			var res1 = count.concat(newyear);
			var totla = res.concat(res1);
			$("#mgs").text(totla);
			$("#mgs").css('color', 'red');
		});


			// Input Button jQuery
			$(".adddata").click(function(){
				$(".showform").slideToggle(100);
			});

			// $(".adddata").on('click', function(e){
			// 	$(".showform").css('display', 'block');
			// 	e.preventDefault();
			// });


		//$(".edit").click(function(e)
		$(document).on("click", ".editgreen", function(e)
		{
			var id 		= $(this).attr("data-id");
			var formURL = "";
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : {id:id},
				dataType: "json",
				success:function(data){
					$('.showform').css('display', 'block');
					$('#id').val(data.id);
					$('#financialyear').val(data.financialyear);
					$('#startmmonth').val(data.startmmonth);
					$('#endmonth').val(data.endmonth);
					$('#rate').val(data.rate);
					$(".update").text("Update");
				}
			});

			e.preventDefault();
		});
    </script>
    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
    <script>
        
    </script>
@endsection
