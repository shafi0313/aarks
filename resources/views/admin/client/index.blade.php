@extends('admin.layout.master')
@section('title', 'Client List')
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
                        <a href="#">Client</a>
                    </li>
                    <li class="active">Client List</li>
                </ul><!-- /.breadcrumb -->

                {{-- <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
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
                                <div class="table-header">
                                    All Client
                                    @can('admin.client.create')
                                        <a class="table-header bg-success"
                                            style="float: right !important; padding-right: 20px; background: #5cb85c"
                                            href="{{ route('client.create') }}">
                                            <i class="fa fa-plus"></i>
                                            Add Client
                                        </a>
                                    @endcan
                                </div>

                                <!-- div.table-responsive -->

                                <!-- div.dataTables_borderWrap -->
                                <div>
                                    @include('admin._client_index_table', ['from' => 'client_index'])
                                </div>
                            </div>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <!-- Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="" style="display: flex; justify-content: space-between; align-items: center; margin: 15px">
                    <h5 class="modal-title" id="noteModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="noteForm">
                    @csrf
                    <input type="hidden" class="client_id">
                    <div class="modal-body">
                        <textarea class="form-control note-text" rows="5"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.note').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('.client_id').val(id);
                $.ajax({
                    url: "{{ route('client.get_note') }}",
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        $('#noteModal .modal-title').text(res.client);
                        $('#noteModal .modal-body textarea').val(res.clientNote);
                    }
                });
            });

            $('#noteForm').submit(function(e) {
                e.preventDefault();
                var client_id = $('.client_id').val();
                var note = $('#noteModal .modal-body textarea').val();
                $.ajax({
                    url: "{{ route('client.note_store') }}",
                    type: 'POST',
                    data: {
                        client_id: client_id,
                        note: note
                    },
                    success: function(data) {
                        $('#noteModal').modal('hide');
                    }
                });
            });
        });
    </script>
@endsection
