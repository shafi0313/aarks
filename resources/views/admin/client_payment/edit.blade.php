@extends('admin.layout.master')
@section('title', 'Client Payment Edit')
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
                    <li class="active">Client Payment Edit</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('client_payment_update', $payment->id) }}" method="post"
                            enctype="multipart/form-data" role="form">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <p>Client Name:<b> {{ $client->fullname }}</b></p>
                            </div>
                            <div class="form-group">
                                <label for="started_at">Payment Date</label>
                                <input type="text" class="form-control datepicker" name="started_at" id="started_at"
                                    value="{{ $payment->started_at->format('d/m/Y h:i:s') }}">
                            </div>
                            <div class="form-group">
                                <label for="subscription_id">Package Name</label>
                                <select name="subscription_id" id="subscription_id" class="form-control">
                                    <option value="">Select Package</option>
                                    @forelse ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ $plan->id == $payment->subscription_id ? ' selected' : '' }}
                                            data-amount="{{ $plan->amount }}">{{ $plan->name }}</option>
                                    @empty
                                        <option value="">Subscription Table Empty</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="duration">Enter Remaining Day</label>
                                <input type="number" class="form-control" name="duration" id="duration"
                                    value="{{ (strtotime($payment->expire_at->format('Y-m-d')) - strtotime(now()->format('Y-m-d'))) / 60 / 60 / 24 }}">
                            </div>
                            <div class="form-group">
                                <label for="amount">Total Paid</label>
                                <input type="number" step="any" class="form-control" name="amount" id="amount"
                                    value="{{ number_format($payment->amount, 2, '.', '') }}">
                            </div>
                            <div class="form-group">
                                <label for="payslip">Payslip</label>
                                <input type="number" step="any" class="form-control" name="payslip" id="payslip"
                                    value="{{ $payment->payslip }}">
                            </div>
                            <div class="form-group">
                                <label for="message">Comment</label>
                                <input type="text" class="form-control" name="message" id="message"
                                    value="{{ $payment->message }}">
                            </div>
                            <div class="form-group">
                                Payment Date: {{ $payment->started_at->diffForHumans() }},
                                {{ $payment->started_at->format('D d
                                                            M, Y h:i:s A') }},
                            </div>
                            <div class="form-group">
                                Valid Till: {{ $payment->expire_at->format('D d M, Y h:i:s A') }},
                            </div>
                            <div class="form-group">
                                Package Name: <div class="badge badge-success p-2 rounded">
                                    {{ $payment->subscription->name }}
                                </div>
                            </div>
                            <div class="form-group">
                                <h3>Remaining Sales</h3>
                                <table class="table table-striped table-bordered table-hovered">
                                    <tr>
                                        <th>Quation</th>
                                        <th>Invoice</th>
                                        <th>Receipt</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="sales_quotation"
                                                id="sales_quotation" value="{{ $quation }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="invoice"
                                                id="invoice" value="{{ $invoice }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="receipt"
                                                id="receipt" value="{{ $receipt }}">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group">
                                <h3>Remaining Purchase</h3>
                                <table class="table table-striped table-bordered table-hovered">
                                    <tr>
                                        <th>Quation</th>
                                        <th>Bill</th>
                                        <th>Payment</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" step="any" class="form-control"
                                                name="purchase_quotation" id="purchase_quotation"
                                                value="{{ $bill_quation }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="bill"
                                                id="bill" value="{{ $bill }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="payment"
                                                id="payment" value="{{ $bill_payment }}">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group">
                                <label for="valid_till">Valid Till</label>
                                <input type="text" class="form-control" name="valid_till" id="valid_till" disabled
                                    value="{{ (strtotime($payment->expire_at->format('Y-m-d')) - strtotime($payment->started_at->format('Y-m-d'))) / 60 / 60 / 24 }}">
                            </div>
                            <div class="form-group">
                                <img src="{{ asset($payment->rcpt) }}" class="img-fluid img-responsive"
                                    alt="{{ $payment->pack_name }}" width="300px">
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <textarea readonly cols="30" class="form-control" rows="6">{{ $payment->message }}</textarea>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <a href="javascript:void()" class="btn btn-danger">
                                        <i class="fa fa-trash-o"></i> Delete
                                    </a>
                                </div>
                                <div class="col-lg-3 pull-right text-right">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
