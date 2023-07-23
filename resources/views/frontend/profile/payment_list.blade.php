@extends('frontend.layout.master')
@section('content')
<?php $p="mp"; $mp="setting";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
<div class="row">
                <div class="col-xs-12">
                    @if($errors->all())
                    <div class="alert alert-block alert-danger">
                        {{ $errors->first() }}
                    </div>
                    @endif
                </div><!-- PAGE CONTENT ENDS -->
            </div>
            <div class="col-md-12">
                <h1 class="text-info"> ALL Payment List </h1>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered item-table">
                    <thead>
                        <tr>
                            <th style="text-align:center;">SL</th>
                            <th style="text-align:center;">Package Name</th>
                            <th style="text-align:center;">Amount</th>
                            <th style="text-align:center;">Duration</th>
                            <th style="text-align:center;">Note</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Renew Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payLists as $i => $paylist)
                        <tr>
                            <td align="center">{{$i+1}}</td>
                            <td align="center">
                                {{$paylist->subscription->name}}
                            </td>
                            <td align="center">{{number_format($paylist->amount,2)}}</td>
                            <td align="center">{{$paylist->duration}}</td>
                            <td align="center">{{$paylist->message}}</td>
                            <td align="center">{{$paylist->status==1?'Active':'Expired'}}</td>
                            <td align="center">{{$paylist->created_at->addMonth($paylist->duration)->diffForHumans()}}</td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Page Content End -->
<script>
    $(".add-item").on('click', function(e){
    var bsb_number		 = $('#bsb_number').val();
    var account_number   = $('#account_number').val();
    if(bsb_number == ""){
        toast("error","BSB Number Can not be empty!");
        $("#bsb_number").focus();
        return false;
    }
    if(account_number == ""){
        toast("error","Account Number  Can not be empty!");
        $("#account_number").focus();
        return false;

    }
});

</script>
<!-- Footer Start -->

<!-- Footer End -->

@stop
