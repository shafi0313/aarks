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

                <li>Report</li>
                <li>Periodic GST/BAS(Accrued)</li>
                <li class="active">{{ $profession->name }}</li>
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

                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> Clients
                                        Detail <strong class="pull-right"><a class="back"
                                                href="https://www.aarks.com.au/Gst_balance_with_period"><i
                                                    class="glyphicon glyphicon-chevron-left"></i> Back</a></strong></h3>
                                </div>
                                <div class="panel-body" style="padding:0px;">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>Company Name</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>ABN No</th>
                                            <th>TFN</th>
                                            <th>Date Of Birth</th>
                                            <th>Phone Number</th>
                                        </tr>
                                        <tr>
                                            <td>{{$client->company}}</td>
                                            <td>{{$client->first_name}}</td>
                                            <td>{{$client->last_name}}</td>
                                            <td>{{$client->email}}</td>
                                            <td>{{$client->abn_number}}</td>
                                            <td>{{$client->tax_file_number}}</td>
                                            <td>{{$client->birthday}}</td>
                                            <td>{{$client->phone}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>



        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i> GST BAS
                </h3>
            </div>
            <div class="panel-body" style="padding:0px;">
                <form method="get" action="{{route('accrued_periodic.report')}} ">
                    @csrf
                    <input type="hidden" name="profession_id" value="{{$profession->id}} " />
                    <input type="hidden" name="client_id" value="{{$client->id}}" />
                    <table class="table table-bordered table-hover">
                    <tr>
                        <th>Period Date to Date</th>
                    </tr>
                    @foreach ($periods as $period)
                    <tr>
                        <td>
                            {{$period->first()->year}} =>

                            @foreach ($period as $per)
                            <div class="checkbox-inline px-4">
                                <label>
                                    <input type="checkbox" name="peroid_id[]" value="{{$per->id}} ">{{$per->start_date->format(aarks('frontend_date_format'))}}  <span class="text-info">To</span>
                                    {{$per->end_date->format(aarks('frontend_date_format'))}}
                                </label>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            <button class="submitbutton btn btn-info form-control" type="submit">Period Report</button>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </div>

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
    jQuery(function($) {
			//initiate dataTables plugin
			var oTable1 =
			$('#dynamic-table')
			//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
			.dataTable( {
				bAutoWidth: false,
				"aoColumns": [
				{ "bSortable": false },
				null, null, null, null, null, null,
				{ "bSortable": false }
			],
			"aaSorting": [],

				//,
				//"sScrollY": "200px",
				//"bPaginate": false,

				//"sScrollX": "100%",
				//"sScrollXInner": "120%",
				//"bScrollCollapse": true,
				//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
				//you may want to wrap the table inside a "div.dataTables_borderWrap" element

				//"iDisplayLength": 50
			} );
			//oTable1.fnAdjustColumnSizing();


			//TableTools settings
			TableTools.classes.container = "btn-group btn-overlap";
			TableTools.classes.print = {
				"body": "DTTT_Print",
				"info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
				"message": "tableTools-print-navbar"
			}

			//initiate TableTools extension
			var tableTools_obj = new $.fn.dataTable.TableTools( oTable1, {
				"sSwfPath": "assets/swf/copy_csv_xls_pdf.swf",

				"sRowSelector": "td:not(:last-child)",
				"sRowSelect": "multi",
				"fnRowSelected": function(row) {
					//check checkbox when row is selected
					try { $(row).find('input[type=checkbox]').get(0).checked = true }
					catch(e) {}
				},
				"fnRowDeselected": function(row) {
					//uncheck checkbox
					try { $(row).find('input[type=checkbox]').get(0).checked = false }
					catch(e) {}
				},

				"sSelectedClass": "success",
				"aButtons": [
					{
						"sExtends": "copy",
						"sToolTip": "Copy to clipboard",
						"sButtonClass": "btn btn-white btn-primary btn-bold",
						"sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
						"fnComplete": function() {
							this.fnInfo( '<h3 class="no-margin-top smaller">Table copied</h3>\
								<p>Copied '+(oTable1.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
								1500
							);
						}
					},

					{
						"sExtends": "csv",
						"sToolTip": "Export to CSV",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
					},

					{
						"sExtends": "pdf",
						"sToolTip": "Export to PDF",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
					},

					{
						"sExtends": "print",
						"sToolTip": "Print view",
						"sButtonClass": "btn btn-white btn-primary  btn-bold",
						"sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",

						"sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",

						"sInfo": "<h3 class='no-margin-top'>Print view</h3>\
									<p>Please use your browser's print function to\
									print this table.\
									<br />Press <b>escape</b> when finished.</p>",
					}
				]
			} );
			//we put a container before our table and append TableTools element to it
			$(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));

			//also add tooltips to table tools buttons
			//addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
			//so we add tooltips to the "DIV" child after it becomes inserted
			//flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
			setTimeout(function() {
				$(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
					var div = $(this).find('> div');
					if(div.length > 0) div.tooltip({container: 'body'});
					else $(this).tooltip({container: 'body'});
				});
			}, 200);



			//ColVis extension
			var colvis = new $.fn.dataTable.ColVis( oTable1, {
				"buttonText": "<i class='fa fa-search'></i>",
				"aiExclude": [0, 4],
				"bShowAll": true,
				//"bRestore": true,
				"sAlign": "right",
				"fnLabel": function(i, title, th) {
					return $(th).text();//remove icons, etc
				}

			});

			//style it
			$(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')

			//and append it to our table tools btn-group, also add tooltip
			$(colvis.button())
			.prependTo('.tableTools-container .btn-group')
			.attr('title', 'Show/hide columns').tooltip({container: 'body'});

			//and make the list, buttons and checkboxed Ace-like
			$(colvis.dom.collection)
			.addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
			.find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
			.find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');



			/////////////////////////////////
			//table checkboxes
			$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

			//select/deselect all rows according to table header checkbox
			$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header

				$(this).closest('table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) tableTools_obj.fnSelect(row);
					else tableTools_obj.fnDeselect(row);
				});
			});

			//select/deselect a row when the checkbox is checked/unchecked
			$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
				var row = $(this).closest('tr').get(0);
				if(!this.checked) tableTools_obj.fnSelect(row);
				else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
			});

				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
				e.stopImmediatePropagation();
				e.stopPropagation();
				e.preventDefault();
			});


			//And for the first simple table, which doesn't have TableTools or dataTables
			//select/deselect all rows according to table header checkbox
			var active_class = 'active';
			$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header

				$(this).closest('table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
					else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
				});
			});

			//select/deselect a row when the checkbox is checked/unchecked
			$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
				var $row = $(this).closest('tr');
				if(this.checked) $row.addClass(active_class);
				else $row.removeClass(active_class);
			});



			/********************************/
			//add tooltip for small view action buttons in dropdown menu
			$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

			//tooltip placement on right or left
			function tooltip_placement(context, source) {
				var $source = $(source);
				var $parent = $source.closest('table')
				var off1 = $parent.offset();
				var w1 = $parent.width();

				var off2 = $source.offset();
				//var w2 = $source.width();

				if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
				return 'left';
			}
		});
</script>

@endsection
