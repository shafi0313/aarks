@extends('admin.layout.master')
@section('title', 'Verify & Fixed Transactions')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Tools</li>
                    <li>Verify & Fixed Transactions</li>
                    <li class="active">{{ clientName($client) }}</li>
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
                        <div align="center">
                            <strong style="font-size:20px;">{{ clientName($client) }} </strong><br />
                            From Date : {{ $from_date }} To : {{ $to_date }}
                        </div>
                        <div class="jumbotron_">
                            <!-- PAGE CONTENT BEGINS -->                            
                            <div class="table-header">Verify & Fixed Transactions</div>
                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Transaction Id</th>
                                        <th>Total Debit</th>
                                        <th>Total Credit</th>
                                        <th>Difference Amount</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $debit = $credit = 0;
                                    @endphp
                                    @foreach ($ledgers as $key => $trans)
                                        @php
                                            $gstDebit = $gstCredit = 0;
                                            $first_tran = $trans->first();
                                        @endphp

                                        @foreach ($trans as $tran)
                                            @php
                                                $debit = abs($tran->debit) != 0 ? abs($tran->debit) - abs($tran->gst) : abs($tran->debit);
                                                $credit = abs($tran->credit) != 0 ? abs($tran->credit) - abs($tran->gst) : abs($tran->credit);
                                                $gstDebit += abs($debit);
                                                $gstCredit += abs($credit);
                                                $diff = round($gstDebit) - round($gstCredit);
                                            @endphp
                                        @endforeach

                                        @if ($diff != 0)
                                            <tr>
                                                <td>{{ @$x += 1 }}</td>
                                                <td>{{ $first_tran->date->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('verify_account.tranView', [$first_tran->transaction_id, $first_tran->source]) }}"
                                                        target="_blank">
                                                        {{ $first_tran->transaction_id }}
                                                    </a>
                                                </td>
                                                <td class="text-right">{{ number_format($gstDebit, 2) }}</td>
                                                <td class="text-right">{{ number_format($gstCredit, 2) }}</td>
                                                <td class="text-right">{{ number_format($diff, 2) }}</td>
                                                <td>
                                                    @if ($first_tran->source == 'ADT')
                                                        <a title="Period Edit"
                                                            href="{{ route('edit_period', [$profession->id, $period->id, $client->id]) }}"
                                                            target="_blank" class="btn btn-pripary btn-sm p-0"><i
                                                                class="fa fa-pencil"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- OPN --}}
                                    @foreach ($opns as $opn)
                                        @php
                                            $first_tran = $opn->first();
                                            $debit = abs($opn->sum('debit'));
                                            $credit = abs($opn->sum('credit'));
                                            $diff = round($debit) - round($credit);
                                        @endphp
                                        @if ($diff != 0)
                                            <tr>
                                                <td>{{ @$x += 1 }}</td>
                                                <td>{{ $first_tran->date->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('verify_account.tranView', [$first_tran->transaction_id, $first_tran->source]) }}"
                                                        target="_blank">
                                                        {{ $first_tran->transaction_id }}
                                                    </a>
                                                </td>
                                                <td class="text-right">{{ number_format($debit, 2) }}</td>
                                                <td class="text-right">{{ number_format($credit, 2) }}</td>
                                                <td class="text-right">{{ number_format($diff, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- PAGE CONTENT ENDS -->
                        </div>

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <script></script>
    <!-- inline scripts related to this page -->
@endsection
