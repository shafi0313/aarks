@extends('frontend.layout.master')
@section('title','BS Import')
@section('content')
<?php $p="im"; $mp="bank";?>

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
<div class="row">
    <div class="col-lg-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row" style="margin-top: 20px;">
            <form action="{{route('cbs_import.store')}}" method="POST" enctype="multipart/form-data" class="form-inline">
                {{csrf_field()}}
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input type="hidden" name="profession_id" value="{{$profession->id}}">
                    <input type="file" name="file" class="form-control">

                    @if($errors->has('file'))
                    <small class="text-danger">* {{$errors->first()}}</small>
                    @endif
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <ul>
                        <li style="list-style-type: none;color: red">File can only import if it is CSV(MS-DOS)/CSV(COMMA-delimited) and heading must be same as sample file.</li>
                        <li style="list-style-type: none"><a href="{{asset('example.csv')}}">Download file Format</a></li>
                    </ul>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" type="submit">Import</button>
                </div>
            </form>
        </div>
        <hr>
        <div class="row">
            <style>
                table tr th {
                    padding: 8px 12px !important;
                    text-align: center;
                }
                table tr td {
                    padding: 3px 5px !important;
                }
            </style>
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 15%;">Account Name</th>
                        <th style="width: 8%;">Date</th>
                        <th>Narration</th>
                        <th>Debit</th>
                        <th>Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bank_statements as $bank_statement)
                    <tr>
                        <td>
                            <select class="form-control form-control-sm account_code" name="account_code"
                                data-route="{{route('cbs_import.updateCode', $bank_statement->id)}}">
                                <option value="" style="color: red">No Account Selected</option>
                                @foreach($account_codes as $account_code)
                                <option value="{{$account_code->id}}"
                                    style="color:{{$account_code->type == 1? '#f542e9': 'blue'}}" @if($bank_statement->
                                    client_account_code &&
                                    $bank_statement->client_account_code->code == $account_code->code)
                                    selected @endif>
                                    {{$account_code->name}} - {{$account_code->code}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{$bank_statement->date->format(aarks('frontend_date_format'))}}</td>
                        <td>{{$bank_statement->narration}}</td>
                        <td class="text-right">{{ number_format($bank_statement->debit,2) }}</td>
                        <td class="text-right">{{ number_format($bank_statement->credit,2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="text-align: right;font-weight:bold;" colspan="3">Total: </td>
                        <td style="text-align: right;font-weight:bold;">{{ number_format($bank_statements->sum('debit'),2) }}</td>
                        <td style="text-align: right;font-weight:bold;">{{ number_format($bank_statements->sum('credit'),2) }}</td>
                    </tr>
                </tbody>
            </table>
            <span style="float: right;padding: 5px;"> {{$bank_statements->links()}}</span>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-10">
            <form action="{{route('cbs_import.post')}}" method="POST" class="form-inline">
                {{csrf_field()}}
                <div class="col-md-3"></div>
                <div class="col-md-4 text-success">
                    Select Bank Account
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="bank_account" id="" style="width: 100%;">
                        <option value="">Select Bank Account</option>
                        @foreach($liquid_asset_account_codes as $liquid_asset_account_code)
                        <option value="{{$liquid_asset_account_code->id}}">
                            {{$liquid_asset_account_code->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('bank_account'))
                    <small class="text-danger">* {{$errors->first()}}</small>
                    @endif
                </div>
                <div class="col-md-1 text-right">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input type="hidden" name="profession_id" value="{{$profession->id}}">
                    <input type="hidden" name="period_id" value="{{$period->id}}">
                    <input type="hidden" name="startDate" value="{{$period->start_date}}">
                    <input type="hidden" name="endDate" value="{{$period->end_date}}">
                    <input type="hidden" name="gstMethod" value="{{$client->gst_method}}">
                    <input type="hidden" name="is_gst_enabled" value="{{$client->is_gst_enabled}}">
                    <input type="hidden" name="sba_debit" value="{{$bank_statements->sum('debit')}}">
                    <input type="hidden" name="sba_credit" value="{{$bank_statements->sum('credit')}}">
                    <button type="submit" class="btn btn-primary" name="action" value="post"
                        onclick="return confirmPost()">Post</button>
                </div>
            </form>
            </div>
            <div class="col-lg-2" style="">
                <form action="{{route('cbs_import.delete')}}" method="post">
                    @method('DELETE')
                    {{csrf_field()}}
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input type="hidden" name="profession_id" value="{{$profession->id}}">
                    <button onclick="return  confirmPost()" type="submit" class="btn btn-danger" name="action" value="delete">Delete
                        All</button>
                </form>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
        <br>
        <br>
    </div><!-- /.col -->
</div><!-- /.row -->
        </div>
    </section>
    <!-- Page Content End -->
<script>
$('.account_code').change(function () {
    var  accountCode = $(this).val();

    var  url = $(this).data('route');

    $.ajax({
        url: url,
        data: {"accountCode": accountCode},
        method: "GET",
        success: function (res) {
                    if (res.status == 200){
                        toast('success', res.message);
                    }else {
                        toast('error', res.message);
                    }
        },
        error:err=>{
            toast('error',err.responseJSON.message);
        }
    });
});
function confirmPost() {
    var r = confirm("Please check you are posting from the correct bank account. If correct press 'ok' and if not, 'cancel'!");
    if (r == false) {
        return false;
    }
}
</script>
    <!-- Footer Start -->

    <!-- Footer End -->

@stop
