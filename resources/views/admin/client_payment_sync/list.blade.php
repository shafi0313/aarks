@extends('admin.layout.master')
@section('title', 'Payment Sync')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Add/Edit Data</li>
                    <li>Journal Entry</li>
                    <li><a href="{{ route('journal_entry_client') }}">Client List</a></li>
                    <li class="active">General Ledger List</li>
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
                        <div class="card">
                            <div class="card-header">
                                <h2>PAYMENT TRANSACTIONS LIST</h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-light table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Date</th>
                                            <th>Transaction</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledgers as $ledger)
                                            <tr>
                                                <td>{{ $ledger->chart_id }}</td>
                                                <td>{{ $ledger->date->format('d/m/Y') }}</td>
                                                <td>{{ $ledger->transaction_id }}</td>
                                                <td>{{ $ledger->balance }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan='4' class="text-center">
                                                <form action="{{ route('payment_sync.destroy') }}" method="post">
                                                    @csrf @method('delete')
                                                    <input type="hidden" name="search" value="{{ $tran_id }}">
                                                    <div class="form-group">
                                                        <button onclick="return confirm('Are you sure?')" class="btn btn-danger form-control"
                                                            type="submit">Delete</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
