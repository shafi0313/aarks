@extends('admin.layout.master')
@section('title','Edit Transaction')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>

                <li>
                    <a href="#">Bankstatement  Transaction List</a>
                </li>
                <li class="active">Edit Transaction</li>
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
                <div class="col-md-12 thumbnail" style="min-height:150px;" align="center">
                    <!-- PAGE CONTENT BEGINS -->
                    <form id="imp_add" action="{{route('bst.imp.update',$bst->id)}} " method="post" autocomplete="off">
                        @csrf
                        @method('put')
                        <table class="table table-bordered">
                            <tr>
                                <th style="text-align:center;" width="25%">A/c Code</th>
                                <th style="text-align:center;">Transaction Date</th>
                                <th style="text-align:center;">Particular</th>
                                <th style="text-align:center;" width="15%">Transaction</th>
                                <th style="text-align:center;" width="15%">Debit</th>
                                <th style="text-align:center;" width="15%">Credit</th>
                            </tr>
                            <tr>
                                <td style="width:12%">
                                    <select class="form-control" id="chart_id" name="chart_id">
                                        @php $item= App\ClientAccountCode::where('client_id',$bst->client_id)->orderBy('code')->first();
                                        @endphp
                                        <option value="{{$item->code}}"> {{$item->name}}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="trn_date"
                                        value="{{ $bst->trn_date }}"
                                        readonly="readonly">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="narration"
                                        value="BST NARRATIONS" readonly="readonly">
                                </td>
                                <td>
                                    <input type="text" name="trn_id" class="form-control"
                                        value="{{ $bst->trn_id }}" readonly="readonly">
                                </td>
                                <td>
                                    <input type="text" class="form-control price" id="debit" name="amount_debit"
                                        value="{{$bst->amount_debit}} " />
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="credit" name="amount_credit"
                                        value="{{$bst->amount_credit}} " />
                                </td>
                            </tr>
                        </table>
                        <button type="submit" style="width: 150px" class="btn btn-info btn-sm">Update</button>
                    </form>
                </div> <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->
<script>
    $("#debit").on('keyup', function(){
    var debit = $(this).val();
    $("#credit").attr('disabled', 'disabled');
    $("#credit").val('');
    if(debit ==''){
        $("#credit").removeAttr('disabled', 'disabled');
    }
});
$("#credit").on('keyup', function(){
    var credit = $(this).val();
    $("#debit").attr('disabled', 'disabled');
    $("#debit").val('');
    if(credit ==''){
        $("#debit").removeAttr('disabled', 'disabled');
    }
});
</script>
@endsection
