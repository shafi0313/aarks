@extends('admin.layout.master')
@section('title', 'Client Payment List')
@section('content')

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Admin</li>
                    <li class="active">Client Payment List</li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td>Client Name: </td>
                                        <td><strong>{{ $payment->client->fullname }}</strong></strong></td>
                                        <td rowspan="6" width='600'>
                                            <a href="{{ asset($payment->rcpt) }}" download>
                                                <img src="{{ asset($payment->rcpt) }}" alt=".." class="img-fluid"
                                                    style="width: 500px">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Package Name: </td>
                                        <td><strong>{{ $payment->subscription->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Time Duration: </td>
                                        <td><strong>{{ $payment->duration }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount: </td>
                                        <td><strong>$ {{ number_format($payment->amount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Order Date: </td>
                                        <td><strong>{{ $payment->created_at->format('d/m/Y') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Message:</td>
                                        <td> <strong>{{ $payment->message }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Action</td>
                                        <td align="center">
                                            <a title="Client Payment Approve" class="btn btn-info"
                                                href="{{ route('client_payment_status', $payment->id) }}">{{ $payment->status == 0 ? 'Approve' : 'Approved' }}</a>
                                            <a title="Client Payment delete" onclick="return confirm('Are you sure?')"
                                                class="btn btn-danger"
                                                href="{{ route('client_payment_delete', $payment->id) }}">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
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

@endsection
