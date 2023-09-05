@extends('admin.layout.master')
@section('title', 'Bank Statement List Page')
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
                        Bank Statement List
                    </li>
                    <li>
                        {{ $profession->name }}
                    </li>
                    <li>
                        {{ clientName($client) }}
                    </li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="jumbotron">
                            <div class="table-header">Bank Statement Transaction Lists</div>
                            <table id="dynamic-table" class="table table-striped table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 80px">SL</th>
                                        <th>Transaction Id</th>
                                        <th>Transaction Date</th>
                                        <th class="no-sort" width="80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1 ;@endphp
                                    @foreach ($ledgers->groupBy('transaction_id') as $ledger)
                                        <tr>
                                            <td class="text-center"> {{ $i++ }} </td>
                                            <td>
                                                <a
                                                    href="{{ route('general_ledger.transaction', [$ledger->first()->transaction_id, $ledger->first()->source]) }}">{{ $ledger->first()->transaction_id }}</a>
                                            </td>
                                            <td>{{ $ledger->first()->date->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a title="Lsit Details Only View"
                                                    href="{{ route('bs_tran_list.details', [$client->id, $profession->id, $ledger->first()->transaction_id, $ledger->first()->source, 'only_view' => 'yes']) }}"><i
                                                        class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                                <a title="Lsit Details " title=""
                                                    href="{{ route('bs_tran_list.details', [$client->id, $profession->id, $ledger->first()->transaction_id, $ledger->first()->source]) }}"><i
                                                        class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                                                @if ($ledger->first()->source == 'BST')
                                                    <a title="Lsit Details Delete" onclick="return confirm('Are you sure?')"
                                                        href="{{ route('bs_tran_list.import.delete', [$client->id, $profession->id, $ledger->first()->transaction_id, $ledger->first()->source]) }}"
                                                        class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a title="Lsit Details Delete" onclick="return confirm('Are you sure?')"
                                                        href="{{ route('bs_tran_list.input.delete', [$client->id, $profession->id, $ledger->first()->transaction_id, $ledger->first()->source]) }}"
                                                        class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
@endsection
