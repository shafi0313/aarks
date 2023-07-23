@extends('admin.layout.master')
@section('title', 'Create Client')
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
                    <a href="#">Client List</a>
                </li>
                <li class="active">Create Client</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    Create Client
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <!-- PAGE CONTENT BEGINS -->
                    <form action="{{ route('client.store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="col-md-12 showform">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title"></h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Company /Trust/Partner ship
                                                        Name</label>
                                                    <input type="text" class="form-control" name="company" id="company">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Contact Person<span
                                                            class=" nomenforcontactperson"
                                                            style="color:red;"></span></label>
                                                    <div class="contact_persons">
                                                        <input type="text" class="form-control" name="contact_person"
                                                            value="{{ old('contact_person') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">First Name<span class="notmontory"
                                                            style="color:red;"></span></label>
                                                    <div class="firstname">
                                                        <input type="text" class="form-control" id="f_name"
                                                            name="first_name" value="{{ old('first_name') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Last Name<span
                                                            class="removeclass notmontory"
                                                            style="color:red;"></span></label>
                                                    <div class="lastname">
                                                        <input type="text" class="form-control" name="last_name"
                                                            id="l_name" value="{{ old('last_name') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Date of Birth<span
                                                            class="removeclass notmontory"
                                                            style="color:red;"></span></label>
                                                    <div class="birth">
                                                        <input class="form-control" name="birthday" type="date"
                                                            value="{{ old('birthday') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Phone Number (Max-10) <span
                                                            style="color:red;">*</span></label>
                                                    <input required type="text" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        class="form-control" name="phone" value="{{ old('phone') }}">
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Email Address <span
                                                            style="color:red;">*</span></label>
                                                    <input required type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="person_color">ABN Number (Max-11)<span
                                                            style="color:red;">*</span> <strong class="abn_mgs"
                                                            style="color:red;"></strong></label>
                                                    <input required type="text"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        maxlength="11" class="form-control" name="abn_number"
                                                        value="{{ old('abn_number') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="person_color">Branch<span style="color:red;">*</span>
                                                    </label>
                                                    <input required type="text"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        maxlength="1" class="form-control" name="branch"
                                                        value="{{ old('branch') }}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Tax File Number</label>
                                                    <input type="text" maxlength="10" minlength="6" class="form-control"
                                                        name="tax_file_number" value="{{ old('tax_file_number') }}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Street Address<span
                                                            style="color:red;">*</span></label>
                                                    <input required type="text" class="form-control"
                                                        name="street_address" value="{{ old('street_address') }}">
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Suburb<span
                                                            style="color:red;">*</span></label>
                                                    <input required type="text" class="form-control" name="suburb"
                                                        value="{{ old('suburb') }}">
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">State<span
                                                            style="color:red;">*</span></label>
                                                    <select required class="form-control" name="state">
                                                        <option disabled selected>Please Select State</option>
                                                        <option {{ old('state')=='WA' ? 'selected' : '' }} value="WA">WA
                                                        </option>
                                                        <option {{ old('state')=='NSW' ? 'selected' : '' }} value="NSW">
                                                            NSW
                                                        </option>
                                                        <option {{ old('state')=='VIC' ? 'selected' : '' }} value="VIC">
                                                            VIC
                                                        </option>
                                                        <option {{ old('state')=='SA' ? 'selected' : '' }} value="SA">SA
                                                        </option>
                                                        <option {{ old('state')=='NT' ? 'selected' : '' }} value="NT">NT
                                                        </option>
                                                        <option {{ old('state')=='ACT' ? 'selected' : '' }} value="ACT">
                                                            ACT
                                                        </option>
                                                        <option {{ old('state')=='TAS' ? 'selected' : '' }} value="TAS">
                                                            TAS
                                                        </option>
                                                        <option {{ old('state')=='QLD' ? 'selected' : '' }} value="QLD">
                                                            QLD
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Post Code<span
                                                            style="color:red;">*</span></label>
                                                    <input required maxlength="6" type="text" class="form-control"
                                                        name="post_code" value="{{ old('post_code') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Country<span
                                                            style="color:red;">*</span></label>
                                                    <input required type="text" class="form-control" name="country"
                                                        value="Australia" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="person_color">Web Address</label>
                                                    <input type="text" class="form-control" name="website">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Director Name</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="director_name" value="{{ old('director_name') }}">

                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Director Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="director_address" value="{{ old('director_address') }}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Name</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_name" value="{{ old('agent_name') }}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_address" value="{{ old('agent_address') }}">

                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent Number</label>
                                                    <input type="text" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        class="form-control" autocomplete="off" name="agent_number"
                                                        value="{{ old('agent_number') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Accountant/Tax Agent ABN
                                                        Number</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="agent_abn_number"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        maxlength="11" value="{{ old('agent_abn_number') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Name</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_name" value="{{ old('auditor_name') }}">

                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Address</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_address" value="{{ old('auditor_address') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="dirc_color">Auditor Phone</label>
                                                    <input type="text" class="form-control" autocomplete="off"
                                                        name="auditor_phone"
                                                        oninput="this.value = this.value.replace(/[^\d]/g,'');"
                                                        maxlength="10" value="{{ old('auditor_phone') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="sec_color">Password<span
                                                            style="color:red;">*</span></label>
                                                    <input type="password" class="form-control" autocomplete="off"
                                                        name="password" placeholder="password"
                                                        autocomplete="new-password"
                                                        onblur="this.setAttribute('readonly', 'readonly');"
                                                        onfocus="this.removeAttribute('readonly');" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="sec_color" for="password-confirm">Confirm
                                                        Password<span style="color:red;">*</span></label>
                                                    <input id="password-confirm" type="password" class="form-control"
                                                        name="password_confirmation" required
                                                        onblur="this.setAttribute('readonly', 'readonly');"
                                                        onfocus="this.removeAttribute('readonly');" readonly
                                                        autocomplete="new-password" placeholder="confirm password">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">Service<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <div class="checkbox">
                                                        @foreach ($services as $service)
                                                        <label>
                                                            <input class="ace ace-checkbox-2" type="checkbox"
                                                                name="services[]" value="{{ $service->id }}" @if(old('services')) {{ in_array($service->id, old('services')) == true ? 'checked' : '' }} @endif>
                                                            <span class="lbl">{{ $service->name }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">GST Resgitaration:<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <input {{ old('is_gst_enabled')=='1' ? 'checked' : '' }}
                                                        type="radio" name="is_gst_enabled" value="1" id="gstYes"> Yes
                                                    <input {{ old('is_gst_enabled')=='0' ? 'checked' : '' }}
                                                        type="radio" name="is_gst_enabled" value="0" id="gstNo"> No
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="font-size:20px;">GST Method:<span
                                                            style="color:red;"></span></label>
                                                    <br />
                                                    <input {{ old('gst_method')=='2' ? 'checked' : '' }} type="radio"
                                                        name="gst_method" value="2" class="gstMethod"> Accrued
                                                    <input {{ old('gst_method')=='1' ? 'checked' : '' }} type="radio"
                                                        name="gst_method" value="1" class="gstMethod"> Cash
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label style="font-size:20px;">Profession<span
                                                        style="color:red;"></span></label>
                                                <br />
                                                <div class="checkbox">
                                                    @foreach ($professions as $profession)
                                                    <label>
                                                        <input class="ace ace-checkbox-2" type="checkbox"
                                                            name="professions[]" value="{{ $profession->id }}" @if(old('professions')) {{ in_array($profession->id,
                                                        old('professions')) == true ? 'checked' : '' }} @endif>
                                                        <span class="lbl"> {{ $profession->name }}
                                                        </span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>


                                            <div class="col-md-4" style="padding-top:20px;">

                                                <div class="form-group">
                                                    <button type="submit" id="submit"
                                                        class="btn btn-info submit update">Create</button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div><!-- /.span -->
                    </form>
                </div><!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->

</div>
</div><!-- /.main-content -->

{{-- @include('admin.layout.footer') --}}

<!-- page specific plugin scripts -->
<script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/dataTables.select.min.js') }}"></script>

<!-- ace scripts -->
<script src="{{ asset('admin/assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/ace.min.js') }}"></script>
<script>
    $("#gstNo").on('click', function() {
            $(".gstMethod").attr('disabled', true)
        })
        $("#gstYes").on('click', function() {
            $(".gstMethod").attr('disabled', false)
        })
</script>
<script>
    $("#submit").click(function() {
            if ($("#company").val() == '') {
                alert('Enter First Name & Last Name')
                $("#f_name").attr('required', true)
                $("#l_name").attr('required', true)
            } else if ($("#company").val() != null) {
                $("#f_name").attr('required', false)
                $("#l_name").attr('required', false)
            } else {
                alert('Enter Company /Trust/Partner ship Name')
                $("#company").attr('required', true)
            }
        })
</script>
<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
            //initiate dataTables plugin
            var myTable =
                $('#dynamic-table')
                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
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


                    //"bProcessing": true,
                    //"bServerSide": true,
                    //"sAjaxSource": "http://127.0.0.1/table.php"	,

                    //,
                    //"sScrollY": "200px",
                    //"bPaginate": false,

                    //"sScrollX": "100%",
                    //"sScrollXInner": "120%",
                    //"bScrollCollapse": true,
                    //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                    //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                    //"iDisplayLength": 50


                    select: {
                        style: 'multi'
                    }
                });


            myTable.buttons().container().appendTo($('.tableTools-container'));

            //style the messege box
            var defaultCopyAction = myTable.button(1).action();
            myTable.button(1).action(function(e, dt, button, config) {
                defaultCopyAction(e, dt, button, config);
                $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
            });


            var defaultColvisAction = myTable.button(0).action();
            myTable.button(0).action(function(e, dt, button, config) {

                defaultColvisAction(e, dt, button, config);


                if ($('.dt-button-collection > .dropdown-menu').length == 0) {
                    $('.dt-button-collection')
                        .wrapInner(
                            '<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                        .find('a').attr('href', '#').wrap("<li />")
                }
                $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
            });

            ////

            setTimeout(function() {
                $($('.tableTools-container')).find('a.dt-button').each(function() {
                    var div = $(this).find(' > div').first();
                    if (div.length == 1) div.tooltip({
                        container: 'body',
                        title: div.parent().text()
                    });
                    else $(this).tooltip({
                        container: 'body',
                        title: $(this).text()
                    });
                });
            }, 500);





            myTable.on('select', function(e, dt, type, index) {
                if (type === 'row') {
                    $(myTable.row(index).node()).find('input:checkbox').prop('checked', true);
                }
            });
            myTable.on('deselect', function(e, dt, type, index) {
                if (type === 'row') {
                    $(myTable.row(index).node()).find('input:checkbox').prop('checked', false);
                }
            });




            /////////////////////////////////
            //table checkboxes
            $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

            //select/deselect all rows according to table header checkbox
            $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]')
                .eq(0).on('click', function() {
                    var th_checked = this.checked; //checkbox inside "TH" table header

                    $('#dynamic-table').find('tbody > tr').each(function() {
                        var row = this;
                        if (th_checked) myTable.row(row).select();
                        else myTable.row(row).deselect();
                    });
                });

            //select/deselect a row when the checkbox is checked/unchecked
            $('#dynamic-table').on('click', 'td input[type=checkbox]', function() {
                var row = $(this).closest('tr').get(0);
                if (this.checked) myTable.row(row).deselect();
                else myTable.row(row).select();
            });



            $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
                e.stopImmediatePropagation();
                e.stopPropagation();
                e.preventDefault();
            });



            //And for the first simple table, which doesn't have TableTools or dataTables
            //select/deselect all rows according to table header checkbox
            var active_class = 'active';
            $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function() {
                var th_checked = this.checked; //checkbox inside "TH" table header

                $(this).closest('table').find('tbody > tr').each(function() {
                    var row = this;
                    if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0)
                        .prop('checked', true);
                    else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop(
                        'checked', false);
                });
            });

            //select/deselect a row when the checkbox is checked/unchecked
            $('#simple-table').on('click', 'td input[type=checkbox]', function() {
                var $row = $(this).closest('tr');
                if ($row.is('.detail-row ')) return;
                if (this.checked) $row.addClass(active_class);
                else $row.removeClass(active_class);
            });



            /********************************/
            //add tooltip for small view action buttons in dropdown menu
            $('[data-rel="tooltip"]').tooltip({
                placement: tooltip_placement
            });

            //tooltip placement on right or left
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                //var w2 = $source.width();

                if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
                return 'left';
            }




            /***************/
            $('.show-details-btn').on('click', function(e) {
                e.preventDefault();
                $(this).closest('tr').next().toggleClass('open');
                $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass(
                    'fa-angle-double-up');
            });
            /***************/





            /**
                 //add horizontal scrollbars to a simple table
                 $('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
                 {
    				horizontal: true,
    				styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
    				size: 2000,
    				mouseWheelLock: true
    			  }
                 ).css('padding-top', '12px');
                 */


        })
</script>

@endsection
