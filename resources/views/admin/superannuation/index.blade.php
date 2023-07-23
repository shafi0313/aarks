@extends('admin.layout.master')
@section('title','Superannuation')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>

                <li>Admin</li>
                <li>Manage Payroll</li>
                <li class="active">Superannuation</li>
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
                        <button type="button" class="btn btn-success adddata" data-toggle="modal" data-target="#myModal"><i class="ace-icon fa fa-plus"></i> Add Super Category</button>
                    </div>

                    <!-- div.table-responsive -->

                    <!-- div.dataTables_borderWrap -->

<!-- Modal -->

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Superannuation</h4>
            </div>
            <form role="form" action=" {{route('superannuation.store')}} " method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="col-sm-3 control-label text-right">Superannuation
                                    Name<strong style="color:red;">*</strong></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                    <strong class="duplicat"></strong>
                                    @error('name')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:0  20px 10px 0">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="e_rate" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="e_rate" name="e_rate">
                                    @error('e_rate')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="e_tools" class="col-sm-4 control-label" align="right">Percent of</label>
                                <div class="col-sm-6">
                                    <select name="e_tools" id="e_tools" class="form-control">
                                        <option value="BS" selected>Basic Sallary</option>
                                        <option value="GH">Gross Hours</option>
                                    </select>
                                    @error('e_tools')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="e_fix_amt" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="e_fix_amt" name="e_fix_amt">
                                    @error('e_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="e_period" class="col-sm-4 control-label" align="right">Dollars Per</label>
                                <div class="col-sm-6">
                                    <select name="e_period" id="e_period" class="form-control">
                                        <option value="Pay Period" selected>Pay Period</option>
                                        {{-- <option value="Per Hours">Per Hours</option>
                                        <option value="Per Month">Per Month</option>
                                        <option value="Per Forthnighty">Per Forthnighty</option> --}}
                                    </select>
                                    @error('e_period')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="e_excl_amt" class="col-sm-3 control-label" align="right">Exclusions: Exclude
                                    the first</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="e_excl_amt" name="e_excl_amt">
                                    @error('e_excl_amt')<span class="text-danger">{{$message}} </span>@enderror

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label" align="left">of eligible wages
                                    from</label>

                            </div>
                        </div>
                    </div>
                    {{-- <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="t_rate" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="t_rate" name="t_rate">
                                    @error('t_rate')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="t_tools" class="col-sm-4 control-label" align="right">Percent Per</label>
                                <div class="col-sm-6">
                                    <select name="t_tools" id="t_tools" class="form-control">
                                        <option value="BS" selected>Basic Sallary</option>
                                        <option value="GH">Gross Hours</option>
                                    </select>
                                    @error('t_tools')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="t_fix_amt" class="col-sm-6 control-label" align="right">Equals</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="t_fix_amt" name="t_fix_amt">
                                    @error('t_fix_amt')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="t_period" class="col-sm-4 control-label" align="right">Dollars Per</label>
                                <div class="col-sm-6">
                                    <select name="t_period" id="t_period" class="form-control">
                                        <option value="Pay Period" selected>Pay Period</option>
                                        <option value="Per Hours">Per Hours</option>
                                        <option value="Per Month">Per Month</option>
                                        <option value="Per Forthnighty">Per Forthnighty</option>
                                    </select>
                                    @error('t_period')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="row" style="padding:0  20px 10px 0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="t_excl_amt" class="col-sm-5 control-label" align="right">Threshold:
                                    Calculate once eligible wages of
                                    paid</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="t_excl_amt" name="t_excl_amt">
                                    @error('t_excl_amt')<span class="text-danger">{{$message}} </span>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label" align="left">of eligible wages
                                    from</label>

                            </div>
                        </div>
                    </div> --}}
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center">SN</th>
                                <th>Superannuation Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $x=1 @endphp
                            @forelse ($sups as $sup)
                            <tr>
                                <td class="center">{{ $x++ }}</td>
                                <td>{{ $sup->name }}</td>
                                <td>
                                    <div class="  action-buttons">
                                        <a  title="Super Annuation Edit" class="green" href=" {{route('superannuation.edit',$sup->id)}} "><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                                        <a  title="Super Annuation Delete" class="red" href=" {{route('superannuation.delete',$sup->id)}} " onclick="return confirm('Are You Sure?')"><i class="ace-icon fa fa-trash bigger-130"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" >
                                    <h1 class="text-danger display-2 text-center"> TABLE EMPTY 🙄</h1>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->




<!-- inline scripts related to this page -->
<script type="text/javascript">


$("#e_rate").on('keyup',e=>{
    let e_rate = $("#e_rate").val();
    if(e_rate.length >= 1){
        $('#e_fix_amt').attr('disabled', 'disabled');
    }else{
        $('#e_fix_amt').removeAttr('disabled', 'disabled');
    }
});
$("#e_fix_amt").on('keyup',e=>{
    let e_rate = $("#e_fix_amt").val();
    if(e_rate.length >= 1){
        $('#e_rate').attr('disabled', 'disabled');
    }else{
        $('#e_rate').removeAttr('disabled', 'disabled');
    }
});

$("#t_rate").on('keyup',e=>{
    let e_rate = $("#t_rate").val();
    if(e_rate.length >= 1){
        $('#t_fix_amt').attr('disabled', 'disabled');
    }else{
        $('#t_fix_amt').removeAttr('disabled', 'disabled');
    }
});
$("#t_fix_amt").on('keyup',e=>{
    let e_rate = $("#t_fix_amt").val();
    if(e_rate.length >= 1){
        $('#t_rate').attr('disabled', 'disabled');
    }else{
        $('#t_rate').removeAttr('disabled', 'disabled');
    }
});






            jQuery(function($) {
        				//initiate dataTables plugin
        				var oTable1 =
        				$('#dynamic-table')
        				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
        				.dataTable( {
        					bAutoWidth: false,
        					"aoColumns": [
        					  { "bSortable": false },
        					  null,
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
        					"aiExclude": [0, 6],
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

        			})

</script>




@endsection
