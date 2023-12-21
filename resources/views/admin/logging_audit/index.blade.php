@extends('admin.layout.master')
@section('title', 'Logging Audit')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Logging Audit</li>
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
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div class="table-header" style="text-align: center;">
                                    <strong>Logging Audit</strong>
                                </div>

                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    <div class="table-responsive">
                                        <table id="data_table" class="table table-striped table-bordered">
                                            <thead>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
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


    @push('custom_scripts')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" /> --}}

        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script>
            $(function() {
                $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // // responsive: true,
                    paging: true,
                    // searchable: true,
                    // scrollY: 400,
                    ajax: "{{ route('logging-infos.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'SL',
                            className: "text-center",
                            width: "17px",
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'user_id',
                            name: 'user_id',
                            title: 'User Email',
                            render: function(data, type, full, meta) {
                                if (full.user_type === 'client') {
                                    return full.client_user.email;
                                } else {
                                    return full.admin_user.email;
                                }
                            },
                        },
                        {
                            data: 'user_id',
                            name: 'user_id',
                            title: 'User Name',
                            render: function(data, type, full, meta) {
                                if (full.user_type === 'client') {
                                    return full.client_user.company ?? full.client_user.first_name + ' ' + full.client_user.last_name ;
                                } else {
                                    return full.admin_user.name;
                                }
                            },
                        },
                        {
                            data: 'user_type',
                            name: 'user_type',
                            title: 'User Type',
                        },
                        {
                            data: 'login_at',
                            name: 'login_at',
                            title: 'Date Time',
                        },
                        {
                            data: 'logout_at',
                            name: 'logout_at',
                            title: 'Session End',
                        },
                        {
                            data: 'duration',
                            name: 'duration',
                            title: 'Duration',
                        },
                        {
                            data: 'mfa_code',
                            name: 'mfa_code',
                            title: 'MFA Code',
                        },
                        {
                            data: 'attempt',
                            name: 'attempt',
                            title: 'Attempt',
                        },
                        {
                            data: 'system_locked',
                            name: 'system_locked',
                            title: 'System Locked',
                        },
                        {
                            data: 'mfa',
                            name: 'mfa',
                            title: 'MFA',
                        },
                        {
                            data: 'ip_address',
                            name: 'ip_address',
                            title: 'IP Address',
                        },
                        {
                            data: 'area',
                            name: 'area',
                            title: 'Country',
                        },
                        {
                            data: 'browser',
                            name: 'browser',
                            title: 'Browser',
                        },
                        {
                            data: 'os',
                            name: 'os',
                            title: 'OS',
                        },
                        {
                            data: 'device',
                            name: 'device',
                            title: 'Device',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            className: "text-center",
                            width: "60px",
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    // fixedColumns: false,
                    scroller: {
                        loadingIndicator: true
                    }
                });
            });
        </script>
    @endpush

@endsection
