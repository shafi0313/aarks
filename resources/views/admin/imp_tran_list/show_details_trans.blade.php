@extends('admin.layout.master')
@section('title','Data Store Transaction View')
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
            <div class="row">
                <div class="col-xs-12">
                    <h1>BankStatement Transaction View</h1>
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 8%">Date</th>
                                    <th style="width: 12%;text-align:center;">Naration</th>
                                    <th style="width: 9%;text-align:center;">Dr Amt</th>
                                    <th style="width: 9%;text-align:center;">Cr Amt</th>
                                    <th style="width: 9%;text-align:center;">GST Dr(Accrd)</th>
                                    <th style="width: 9%;text-align:center;">GST Cr(Accrd)</th>
                                    <th style="width: 9%;text-align:center;">GST Dr(Cash)</th>
                                    <th style="width: 9%;text-align:center;">GST Cr(Cash)</th>
                                    <th style="width: 10%;text-align:center;">Net Amt(Dr)</th>
                                    <th style="width: 10%;text-align:center;">Net Amt(Cr)</th>
                                    <th style="width: 5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $i=> $data)
                                <tr>
                                    <td>{{$i+=1}} </td>
                                    <td>{{$codeName->name}} </td>
                                    <td class="text-right">{{ number_format($data->amount_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->amount_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_accrued_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_accrued_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_cash_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->gst_cash_credit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->net_amount_debit,2) }} </td>
                                    <td class="text-right">{{ number_format($data->net_amount_credit,2) }} </td>
                                    {{-- <td>{{$data->id}} </td> --}}
<td>
    <div align="center" class="  action-buttons" style="font-size: 12px">
        <a  title="BST Edit" class="green" href="{{route('bst.imp.edit',$data->id)}} ">
            <i class="ace-icon fa fa-pencil bigger-130"></i>
        </a>
        {{-- <a class="_delete" data-route="{{route('bst.imp.delete',$data->id)}}"></a> --}}
        <a class="red" title="BST Delete" href="{{route('bst.imp.delete',$data->id)}} " onclick="return confirmPost()">
        <i class="ace-icon fa fa-trash-o bigger-130"></i>
        </a>
    </div>
</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
<script>
function confirmPost() {
    var r = confirm("Please check you are Deleting the correct bank account. If correct press 'ok' and if not,'cancel'!");
    if (r == false) {
        return false;
    }
}
</script>
@endsection
