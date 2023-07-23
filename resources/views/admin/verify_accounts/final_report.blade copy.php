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
                    <li class="active">All Transactions</li>
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
                        <div align="center">
                            <strong style="font-size:18px;">{{ $client->fullname }} </strong><br />
                            From Date : {{ $from_date }} To : {{ $to_date }}
                        </div>
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Transaction Id</th>
                                    <th>Total Debit</th>
                                    <th>Total Credit</th>
                                    <th>Difference Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ledgers as $trans)
                                    @php
                                        $sum_debit = $sum_credit = $ldebit = $lcredit = $Bdebit = $Bcredit = $index = 0;
                                        
                                        $first_tran = $trans->first();
                                        if ($first_tran->source == 'INP' || $first_tran->source == 'BST') {
                                            $bank = \App\Models\GeneralLedger::where('client_id', $first_tran->client_id)
                                                ->where('profession_id', $first_tran->profession_id)
                                                ->where('transaction_id', $first_tran->transaction_id)
                                                ->whereNotIn('chart_id', $trans->pluck('chart_id')->toArray())
                                                ->where('chart_id', 'like', '551%')
                                                ->first(ledgerSetVisible());
                                        } else {
                                            $bank = \App\Models\GeneralLedger::where('client_id', $first_tran->client_id)
                                                ->where('profession_id', $first_tran->profession_id)
                                                ->where('transaction_id', $first_tran->transaction_id)
                                                ->where('chart_id', 'like', '551%')
                                                ->first(ledgerSetVisible());
                                        }
                                        $loan = \App\Models\GeneralLedger::where('transaction_id', $first_tran->transaction_id)
                                            ->where('client_id', $first_tran->client_id)
                                            ->where('profession_id', $first_tran->profession_id)
                                            ->where('chart_id', 954100)
                                            ->first(ledgerSetVisible());
                                    @endphp

                                    @if ($loan && $first_tran->source == 'ADT')
                                        @php
                                            $ldebit = abs($loan->debit);
                                            $lcredit = abs($loan->credit);
                                        @endphp
                                    @endif

                                    @if ($bank && ($first_tran->source == 'BST' || $first_tran->source == 'INP'))
                                        @php
                                            $Bdebit = abs($bank->debit);
                                            $Bcredit = abs($bank->credit);
                                        @endphp
                                    @endif

                                    @foreach ($trans as $tran)
                                        @php
                                            $debit = $credit = 0;
                                            if ($tran->balance_type == 1) {
                                                if ($tran->balance < 0) {
                                                    $credit = $tran->balance;
                                                } else {
                                                    $debit = $tran->balance;
                                                }
                                            } elseif ($tran->balance_type == 2) {
                                                if ($tran->balance < 0) {
                                                    $debit = $tran->balance;
                                                } else {
                                                    $credit = $tran->balance;
                                                }
                                            }
                                            $sum_debit += abs($debit);
                                            $sum_credit += abs($credit);
                                            // echo $tran->transaction_id.' => '.$debit;
                                        @endphp
                                    @endforeach
                                    @php
                                        $total_debit = abs($sum_debit) + abs($ldebit) + abs($Bdebit);
                                        $total_credit = abs($sum_credit) + abs($lcredit) + abs($Bcredit);
                                        $diff = round($total_credit - $total_debit);
                                        // echo $first_tran->transaction_id.' => '.$diff;
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
                                            <td>{{ number_format($total_debit, 2) }}</td>
                                            <td>{{ number_format($total_credit, 2) }}</td>
                                            <td>{{ number_format($diff, 2) }}</td>
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
                            </tbody>
                        </table>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <script>
        
    </script>
    <!-- inline scripts related to this page -->
@endsection
