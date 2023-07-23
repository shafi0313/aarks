@extends('admin.layout.master')
@section('title','Transaction View')
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
                        <a href="#">Business Activity</a>
                    </li>
                    <li>
                        <a href="#"></a>
                    </li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">

                <!-- Settings -->
            {{--             <h1>Hello</h1>@include('admin.layout.settings')--}}
            <!-- /Settings -->

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <h1>Transaction View</h1>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                <tr>

                                    <th class="center">SN</th>
                                    <th>Account Name</th>
                                    <th>Trn.Date</th>
                                    <th>Trn.ID</th>
                                    <th>Particular</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sum = ['debit' => 0, 'credit' => 0];
                                    $ldebit =$lcredit = $Bdebit =$Bcredit = $index = 0;
                                @endphp
                                @foreach($transactions as $transaction)
                                    @php
                                    $debit=$credit=0;
                                        if($transaction->balance_type === 1){
                                            if($transaction->tranBlnc < 0){
                                                $credit = $transaction->tranBlnc;
                                            }else{
                                                $debit = $transaction->tranBlnc;
                                            }
                                        }elseif($transaction->balance_type === 2){
                                            if($transaction->tranBlnc < 0){
                                                $debit = $transaction->tranBlnc;
                                            }else{
                                                $credit = $transaction->tranBlnc;
                                            }
                                        }
                                        $sum['debit'] += abs($debit);
                                        $sum['credit'] += abs($credit);
                                    @endphp
                                    <tr>
                                        <td class="center">{{ $index+=1 }}</td>
                                        <td>{{ $transaction->client_account_code->name }}</td>
                                        <td>{{ $transaction->date->format(aarks('frontend_date_format')) }}</td>
                                        <td class="center">
                                            <a href="{{route('general_ledger.data_store.transaction', [$transaction->transaction_id,$transaction->chart_id,$transaction->source])}}"
                                                style="color: green;text-decoration: underline">{{$transaction->transaction_id}}
                                            </a>
                                        </td>
                                        <td>{{ $transaction->narration }}</td>
                                        <td class="text-right">{{ number_format(abs($debit),2) }}</td>
                                        <td class="text-right">{{ number_format(abs($credit),2) }}</td>
                                    </tr>
                                @endforeach
                                @if ($loan && $source == 'ADT')
                                @php
                                    $ldebit = abs($loan->debit);
                                    $lcredit = abs($loan->credit);
                                @endphp
                                    <tr>
                                        <td class="center">{{ $index +=1 }}</td>
                                        <td>Loan from/to Others</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->date)->format('d/m/Y') }}</td>
                                        <td class="center">
                                            <a href="{{route('general_ledger.data_store.transaction', [$loan->transaction_id,$loan->chart_id,])}}"
                                                style="color: green;text-decoration: underline">{{$loan->transaction_id}}
                                            </a>
                                        </td>
                                        <td>{{ $loan->narration }}</td>
                                        <td class="text-right">{{ number_format(abs($loan->debit),2) }}</td>
                                        <td class="text-right">{{ number_format(abs($loan->credit),2) }}</td>
                                    </tr>
                                @endif
                                @if ($bank && ($source == 'BST' || $source == 'INP'))
                                @php
                                    $Bdebit = abs($bank->debit);
                                    $Bcredit = abs($bank->credit);
                                @endphp
                                    <tr>
                                        <td class="center">{{ $index +=1 }}</td>
                                        <td>{{ $bank->client_account_code->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($bank->date)->format('d/m/Y') }}</td>
                                        <td class="center">
                                            <a href="{{route('general_ledger.data_store.transaction', [$bank->transaction_id,$bank->chart_id,])}}"
                                                style="color: green;text-decoration: underline">{{$bank->transaction_id}}
                                            </a>
                                        </td>
                                        <td>{{ $bank->narration }}</td>
                                        <td class="text-right">{{ number_format(abs($bank->debit),2) }}</td>
                                        <td class="text-right">{{ number_format(abs($bank->credit),2) }}</td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Total</b></td>
                                        <td class="text-right">{{ number_format(abs($sum['debit']+abs($ldebit)+abs($Bdebit)),2)}}</td>
                                        <td class="text-right">{{ number_format(abs($sum['credit']+abs($lcredit)+abs($Bcredit)),2) }}</td>
                                        {{--                                        <td></td>--}}
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
@endsection
