@extends('frontend.layout.master')
@section('title','Receive Payment')
@section('content')
<?php $p="bp"; $mp="purchase";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                            <thead class="text-center" style="font-size: 14px">
                                <tr>
                                    <th width="4%">SL</th>
                                    <th width="10%">Customer Name</th>
                                    <th width="15%">Balance</th>
                                    <th width="9%">Enter Receipt</th>
                                    <th width="11%">Phone</th>
                                    <th width="11%">Mobaile</th>
                                    <th width="9%" class="no-sort">E-mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cards as $i => $card)
                                @php
                                    $payment_amount = \App\Models\Frontend\CreditorPaymentReceive::where('client_id', $card->client_id)->where('customer_card_id', $card->id)->where('source', $card->client->invoiceLayout->layout == 2?2:1)->sum('payment_amount');

                                    if ($card->client->invoiceLayout->layout == 2) {
                                        $creditor_sum = $card->creditors->whereNull('job_title')->sum('amount');
                                    } else {
                                        $creditor_sum = $card->creditors->whereNull('item_no')->sum('amount');
                                    }
                                @endphp

                                @if ($card->opening_blnc > 0 || $creditor_sum > 0)
                                <tr>
                                    <td class="text-center">{{ $i+1 }}</td>
                                    <td>{{$card->name}}</td>
                                    <td class="text-center">$ {{number_format(( $creditor_sum + $card->opening_blnc) - $payment_amount,2)}}</td>
                                    <td>
                                        @if ($creditor_sum > 0)
                                        <a href="{{route('spayment.profession', $card->id)}}">Input Payment</a>
                                        @else
                                        <a href="{{route('spayment.form',[$card->id,$card->profession_id])}}?open=true">Input Payment</a>
                                        @endif
                                    </td>
                                    <td>{{$card->phone}}</td>
                                    <td>{{$card->mobile}}</td>
                                    <td>{{$card->email}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->
<!-- Data Table -->
<script>
    $(document).ready(function() {
            $('#example').DataTable( {
                "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
                "order": [[ 0, "asc" ]]
            } );
        } );
</script>
@stop
