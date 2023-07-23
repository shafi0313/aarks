@extends('admin.layout.master')
@section('title', 'Dashboard')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li class="active">Dashboard</li>
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
                <div class="page-header">
                    <h1>Dashboard<small><i class="ace-icon fa fa-angle-double-right"></i>overview &amp; stats</small></h1>
                    <div class="pull-right">
                        <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </div>
                    </div>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        {{-- <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>

                        <i class="ace-icon fa fa-check green"></i>

                        Welcome to
                        <strong class="green">
                            Ace
                            <small>(v1.4)</small>
                        </strong>,
                        <a href="#">github</a>
                    </div> --}}

                        <div class="row">
                            <div class="space-6"></div>
                            <div class="col-sm-7 infobox-container">
                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-shopping-cart"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <span class="infobox-data-number">{{ $adminUser }}</span>
                                        <div class="infobox-content">Admin Users</div>
                                    </div>
                                </div>
                                <div class="infobox infobox-green">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-user"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <span class="infobox-data-number">{{ $clientCount }}</span>
                                        <div class="infobox-content">Client's</div>
                                    </div>
                                </div>
                                <div class="infobox infobox-blue">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-twitter"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <span class="infobox-data-number">{{ $profession }}</span>
                                        <div class="infobox-content">Professions</div>
                                    </div>
                                </div>

                                <div class="infobox infobox-red">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-flask"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">{{ $acCode }}</span>
                                        <div class="infobox-content">Master Account Code</div>
                                    </div>
                                </div>

                                {{-- <div class="infobox infobox-orange2">
                                    <div class="infobox-chart">
                                        <span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
                                    </div>

                                    <div class="infobox-data">
                                        <span class="infobox-data-number">{{ number_format($visitors) }}</span>
                                        <div class="infobox-content">pageviews</div>
                                    </div>

                                    <div class="badge badge-success">
                                        7.2%
                                        <i class="ace-icon fa fa-arrow-up"></i>
                                    </div>
                                </div> --}}

                                {{-- @php
                            $memory_size = memory_get_usage();
                            $memory_unit = array('Bytes','KB','MB','GB','TB','PB');
                            @endphp
                            <div class="infobox infobox-blue2">
                                <div class="infobox-progress">
                                    <div class="easy-pie-chart percentage" data-percent="{{round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2)}}" data-size="46">

                                        <span class="percent" title="{{$memory_unit[$x]}}">{{round($memory_size/pow(1024,($x=floor(log($memory_size,1024)))),2)}}</span>
                                    </div>
                                </div>

                                <div class="infobox-data">
                                    <span class="infobox-text">RAM used</span>

                                    <div class="infobox-content">
                                        <span class="bigger-110">~</span>
                                        {{$meminfo['MemTotal']}}
                                    </div>
                                </div>
                            </div>

                            <div class="space-6"></div> --}}

                                {{-- <div class="infobox infobox-green infobox-small infobox-dark">
                                <div class="infobox-progress">
                                    <div class="easy-pie-chart percentage" data-percent="{{sys_getloadavg()[0]}}" data-size="39">
                                        <span class="percent">{{sys_getloadavg()[0]}}</span>%
                                    </div>
                                </div>

                                <div class="infobox-data">
                                    <div class="infobox-content">CPU</div>
                                    <div class="infobox-content">Used</div>
                                </div>
                            </div> --}}

                                {{-- <div class="infobox infobox-blue infobox-small infobox-dark">
                                <div class="infobox-chart">
                                    <span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
                                </div>

                                <div class="infobox-data">
                                    <div class="infobox-content">Earnings</div>
                                    <div class="infobox-content">$32,000</div>
                                </div>
                            </div> --}}

                                {{-- <div class="infobox infobox-grey infobox-small infobox-dark">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-download"></i>
                                </div>

                                <div class="infobox-data">
                                    <div class="infobox-content">Downloads</div>
                                    <div class="infobox-content">1,205</div>
                                </div>
                            </div> --}}
                            </div>

                            <div class="vspace-12-sm"></div>

                            <div class="col-sm-5">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-flat widget-header-small">
                                        <h5 class="widget-title">
                                            <i class="ace-icon fa fa-signal"></i>
                                            Traffic Sources
                                        </h5>

                                        <div class="widget-toolbar no-border">
                                            <div class="inline dropdown-hover">
                                                <button class="btn btn-minier btn-primary">
                                                    This Week
                                                    <i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
                                                </button>

                                                <ul
                                                    class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
                                                    <li class="active">
                                                        <a href="#" class="blue">
                                                            <i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
                                                            This Week
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i
                                                                class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            Last Week
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i
                                                                class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            This Month
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#">
                                                            <i
                                                                class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
                                                            Last Month
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="piechart-placeholder"></div>

                                            <div class="hr hr8 hr-double"></div>

                                            <div class="clearfix">
                                                <div class="grid3">
                                                    <span class="grey">
                                                        <i class="ace-icon fa fa-facebook-square fa-2x blue"></i>
                                                        &nbsp; likes
                                                    </span>
                                                    <h4 class="bigger pull-right">1,255</h4>
                                                </div>

                                                <div class="grid3">
                                                    <span class="grey">
                                                        <i class="ace-icon fa fa-twitter-square fa-2x purple"></i>
                                                        &nbsp; tweets
                                                    </span>
                                                    <h4 class="bigger pull-right">941</h4>
                                                </div>

                                                <div class="grid3">
                                                    <span class="grey">
                                                        <i class="ace-icon fa fa-pinterest-square fa-2x red"></i>
                                                        &nbsp; pins
                                                    </span>
                                                    <h4 class="bigger pull-right">1,050</h4>
                                                </div>
                                            </div>
                                        </div><!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div><!-- /.widget-box -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="hr hr32 hr-dotted"></div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-flat">
                                        <h4 class="widget-title lighter">
                                            <i class="ace-icon fa fa-star orange"></i>
                                            Quick Links
                                        </h4>
                                    </div>
                                    <style>
                                        .links a {
                                            margin: 0px 10px 10px 0px;
                                        }

                                        .add_edit_entry a {
                                            background: #baccdb !important;
                                            border-color: #baccdb !important;
                                            color: black !important;
                                        }
                                    </style>
                                    <div class="links add_edit_entry">
                                        <a href="{{route('period.index')}}" class="btn btn-primary">Data Entry From Receipt(ADT)</a>
                                        <a href="{{route('bs_import.index')}}" class="btn btn-primary">Import Bank Statement(BST)</a>
                                        <a href="{{route('bs_input.index')}}" class="btn btn-primary">Input Bank Statement(INP)</a>
                                        <a href="{{route('journal_entry_client')}}" class="btn btn-primary">Journal Entry(JNP)</a>
                                        <a href="{{route('depreciation.index')}}" class="btn btn-primary">Depreciation(DEP)</a>
                                        <a href="{{route('budget.index')}}" class="btn btn-primary">Prepare Budget</a>
                                        <a href="{{route('business-plan.index')}}" class="btn btn-primary">Business Plan</a>
                                        <a href="#" class="btn btn-primary">Manage Investment</a>
                                    </div>
                                </div><!-- /.widget-box -->
                            </div><!-- /.col -->

                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="links ">
                                    <a href="{{route('journal_list.index')}}" class="btn btn-success">Journal List</a>
                                    <a href="{{route('bs_tran_list.index')}}" class="btn btn-success">Bank Trn List</a>
                                    <a href="{{route('bank_recon.index')}}" class="btn btn-success">Bank Reconciliation</a>
                                    <a href="{{route('payment_sync.index')}}" class="btn btn-success">Payment trn adv.delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="links ">
                                    <a href="{{route('gst_recon.index')}}" class="btn btn-info">GST Recon for TR</a>
                                    <a href="{{route('cash_basis.index')}}" class="btn btn-info">GST/BAS(Cash)</a>
                                    <a href="{{route('accrued_basis.index')}}" class="btn btn-info">GST/BAS (Cnosol.Acured)</a>
                                    <a href="{{route('cash_periodic.index')}}" class="btn btn-info">Periodic BAS(s/actvCash)</a>
                                    <a href="{{route('accrued_periodic.index')}}" class="btn btn-info">Periodic BAS(s/actvAcur)</a>
                                    <a href="{{route('general_ledger.index')}}" class="btn btn-info">General Ledger</a>
                                    <a href="{{route('trial-balance.index')}}" class="btn btn-info">Trial Balance(S/Activity)</a>
                                    <a href="{{route('console_trial_balance.index')}}" class="btn btn-info">Cnosol.Trial Balance</a>
                                    <a href="{{route('profit_loss_gst_excl.index')}}" class="btn btn-info">P/L(GST ,S/Activity)</a>
                                    <a href="{{route('profit_loss_gst_incl.index')}}" class="btn btn-info">P/L(GST Incl,S/Activity)</a>
                                    <a href="{{route('console_accum.excl_index')}}" class="btn btn-info">Cnosol.P/L(GST Excl)</a>
                                    <a href="{{route('console_accum.incl_index')}}" class="btn btn-info">Cnosol.P/L(GST Incl)</a>
                                    <a href="{{route('balance_sheet.index')}}" class="btn btn-info">Balance Sheet(S/Activity)</a>
                                    <a href="{{route('console_balance_sheet.index')}}" class="btn btn-info">Cnosol.Balance Sheet</a>
                                    <a href="{{route('comperative_balance_sheet.index')}}" class="btn btn-info">Comparative B/S</a>
                                    <a href="{{route('complete_financial_report.index')}}" class="btn btn-info">Complete Financial R.</a>
                                    <a href="{{route('console_financial_report_index')}}" class="btn btn-info">Cnosol.Financial Report.</a>
                                    <a href="{{route('comperative_financial_report_index')}}" class="btn btn-info">Comparative Financial R.</a>
                                    <a href="{{route('depreciation_report.index')}}" class="btn btn-info">Depreciation Report</a>
                                    <a href="#" class="btn btn-info">Advanced Report</a>
                                </div>
                            </div>
                        </div>

                        <div class="hr hr32 hr-dotted"></div>
                        <!-- page specific plugin scripts -->

                        <!--[if lte IE 8]>
                            <script src="{{ asset('assets/js/excanvas.min.js') }}"></script>
                            <![endif]-->
                        <script src="{{ asset('admin/assets/js/jquery-ui.custom.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.easypiechart.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.sparkline.index.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.flot.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.flot.pie.min.js') }}"></script>
                        <script src="{{ asset('admin/assets/js/jquery.flot.resize.min.js') }}"></script>

                        <!-- ace scripts -->
                        {{-- <script src="{{ asset('admin/assets/js/ace-elements.min.js')}}"></script> --}}
                        {{-- <script src="{{ asset('admin/assets/js/ace.min.js')}}"></script> --}}

                        <!-- inline scripts related to this page -->
                        <script type="text/javascript">
                            jQuery(function($) {
                                $('.easy-pie-chart.percentage').each(function() {
                                    var $box = $(this).closest('.infobox');
                                    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css(
                                        'color') : 'rgba(255,255,255,0.95)');
                                    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' :
                                    '#E2E2E2';
                                    var size = parseInt($(this).data('size')) || 50;
                                    $(this).easyPieChart({
                                        barColor: barColor,
                                        trackColor: trackColor,
                                        scaleColor: false,
                                        lineCap: 'butt',
                                        lineWidth: parseInt(size / 10),
                                        animate: ace.vars['old_ie'] ? false : 1000,
                                        size: size
                                    });
                                })

                                $('.sparkline').each(function() {
                                    var $box = $(this).closest('.infobox');
                                    var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
                                    $(this).sparkline('html', {
                                        tagValuesAttribute: 'data-values',
                                        type: 'bar',
                                        barColor: barColor,
                                        chartRangeMin: $(this).data('min') || 0
                                    });
                                });


                                //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
                                //but sometimes it brings up errors with normal resize event handlers
                                $.resize.throttleWindow = false;

                                var placeholder = $('#piechart-placeholder').css({
                                    'width': '90%',
                                    'min-height': '150px'
                                });
                                var data = [{
                                        label: "social networks",
                                        data: 38.7,
                                        color: "#68BC31"
                                    },
                                    {
                                        label: "search engines",
                                        data: 24.5,
                                        color: "#2091CF"
                                    },
                                    {
                                        label: "ad campaigns",
                                        data: 8.2,
                                        color: "#AF4E96"
                                    },
                                    {
                                        label: "direct traffic",
                                        data: 18.6,
                                        color: "#DA5430"
                                    },
                                    {
                                        label: "other",
                                        data: 10,
                                        color: "#FEE074"
                                    }
                                ]

                                function drawPieChart(placeholder, data, position) {
                                    $.plot(placeholder, data, {
                                        series: {
                                            pie: {
                                                show: true,
                                                tilt: 0.8,
                                                highlight: {
                                                    opacity: 0.25
                                                },
                                                stroke: {
                                                    color: '#fff',
                                                    width: 2
                                                },
                                                startAngle: 2
                                            }
                                        },
                                        legend: {
                                            show: true,
                                            position: position || "ne",
                                            labelBoxBorderColor: null,
                                            margin: [-30, 15]
                                        },
                                        grid: {
                                            hoverable: true,
                                            clickable: true
                                        }
                                    })
                                }

                                drawPieChart(placeholder, data);

                                /**
                                 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
                                 so that's not needed actually.
                                 */
                                placeholder.data('chart', data);
                                placeholder.data('draw', drawPieChart);


                                //pie chart tooltip example
                                var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo(
                                    'body');
                                var previousPoint = null;

                                placeholder.on('plothover', function(event, pos, item) {
                                    if (item) {
                                        if (previousPoint != item.seriesIndex) {
                                            previousPoint = item.seriesIndex;
                                            var tip = item.series['label'] + " : " + item.series['percent'] + '%';
                                            $tooltip.show().children(0).text(tip);
                                        }
                                        $tooltip.css({
                                            top: pos.pageY + 10,
                                            left: pos.pageX + 10
                                        });
                                    } else {
                                        $tooltip.hide();
                                        previousPoint = null;
                                    }

                                });

                                /////////////////////////////////////
                                $(document).one('ajaxloadstart.page', function(e) {
                                    $tooltip.remove();
                                });


                                var d1 = [];
                                for (var i = 0; i < Math.PI * 2; i += 0.5) {
                                    d1.push([i, Math.sin(i)]);
                                }

                                var d2 = [];
                                for (var i = 0; i < Math.PI * 2; i += 0.5) {
                                    d2.push([i, Math.cos(i)]);
                                }

                                var d3 = [];
                                for (var i = 0; i < Math.PI * 2; i += 0.2) {
                                    d3.push([i, Math.tan(i)]);
                                }


                                var sales_charts = $('#sales-charts').css({
                                    'width': '100%',
                                    'height': '220px'
                                });
                                $.plot("#sales-charts", [{
                                        label: "Domains",
                                        data: d1
                                    },
                                    {
                                        label: "Hosting",
                                        data: d2
                                    },
                                    {
                                        label: "Services",
                                        data: d3
                                    }
                                ], {
                                    hoverable: true,
                                    shadowSize: 0,
                                    series: {
                                        lines: {
                                            show: true
                                        },
                                        points: {
                                            show: true
                                        }
                                    },
                                    xaxis: {
                                        tickLength: 0
                                    },
                                    yaxis: {
                                        ticks: 10,
                                        min: -2,
                                        max: 2,
                                        tickDecimals: 3
                                    },
                                    grid: {
                                        backgroundColor: {
                                            colors: ["#fff", "#fff"]
                                        },
                                        borderWidth: 1,
                                        borderColor: '#555'
                                    }
                                });


                                $('#recent-box [data-rel="tooltip"]').tooltip({
                                    placement: tooltip_placement
                                });

                                function tooltip_placement(context, source) {
                                    var $source = $(source);
                                    var $parent = $source.closest('.tab-content')
                                    var off1 = $parent.offset();
                                    var w1 = $parent.width();

                                    var off2 = $source.offset();
                                    //var w2 = $source.width();

                                    if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
                                    return 'left';
                                }


                                $('.dialogs,.comments').ace_scroll({
                                    size: 300
                                });


                                //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
                                //so disable dragging when clicking on label
                                var agent = navigator.userAgent.toLowerCase();
                                if (ace.vars['touch'] && ace.vars['android']) {
                                    $('#tasks').on('touchstart', function(e) {
                                        var li = $(e.target).closest('#tasks li');
                                        if (li.length == 0) return;
                                        var label = li.find('label.inline').get(0);
                                        if (label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation();
                                    });
                                }

                                $('#tasks').sortable({
                                    opacity: 0.8,
                                    revert: true,
                                    forceHelperSize: true,
                                    placeholder: 'draggable-placeholder',
                                    forcePlaceholderSize: true,
                                    tolerance: 'pointer',
                                    stop: function(event, ui) {
                                        //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                                        $(ui.item).css('z-index', 'auto');
                                    }
                                });
                                $('#tasks').disableSelection();
                                $('#tasks input:checkbox').removeAttr('checked').on('click', function() {
                                    if (this.checked) $(this).closest('li').addClass('selected');
                                    else $(this).closest('li').removeClass('selected');
                                });


                                //show the dropdowns on top or bottom depending on window height and menu position
                                $('#task-tab .dropdown-hover').on('mouseenter', function(e) {
                                    var offset = $(this).offset();

                                    var $w = $(window)
                                    if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
                                        $(this).addClass('dropup');
                                    else $(this).removeClass('dropup');
                                });

                            })
                        </script>

                    @endsection
