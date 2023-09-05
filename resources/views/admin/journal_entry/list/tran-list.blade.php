@extends('admin.layout.master')
@section('title', 'Journal List')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Transaction List</li>
                    <li>Journal List</li>
                    <li><a href="{{ route('journal_entry_client') }}">Client List</a></li>
                    <li class="active">Select Business Activity</li>
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
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-12">
                                <div class="col-lg-12">
                                    <div class="jumbotron_">
                                        <div class="table-header">Journal Lists</div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>SL </th>
                                                    <th>Journal Serial </th>
                                                    <th>Journal Date</th>
                                                    <th>Journal Ref</th>
                                                    <th>Trn.ID</th>
                                                    <th class="no-sort" width="60px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($journals->groupBy('journal_number') as $journal)
                                                    <?php $journal = $journal->first(); ?>
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>
                                                            <a
                                                                href="{{ route('journal_list_trans_show', [$client->id, $profession->id, $journal->tran_id]) }}">
                                                                {{ invoice($journal->journal_number, 8, 'JNL') }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $journal->date->format('d/m/Y') }}</td>
                                                        <td>{{ $journal->narration }}</td>
                                                        <td>{{ $journal->tran_id }}</td>
                                                        <td class="text-center">
                                                            @can('admin.journal_list.edit')
                                                                <a title="Journal List Edit"
                                                                    href="{{ route('journal_list_edit', $journal->tran_id) }}"
                                                                    class="fa fa-pencil btn btn-info btn-sm"></a>
                                                            @endcan
                                                            @can('admin.journal_list.delete')
                                                                <form action="{{ route('journal_list_delete', $journal->id) }}"
                                                                    method="post" style="display: inline-block;">
                                                                    @csrf @method('delete')
                                                                    <button onclick="return confirm('You will lost all data!')"
                                                                        title="Journal List Delete" type="submit"
                                                                        class="btn btn-danger btn-sm"><i
                                                                            class="fa fa-trash "></i></button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
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

    <!-- Script -->
@stop
