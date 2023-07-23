@extends('admin.layout.master')
@section('title', 'Plan Create')
@section('style')
<style>
    .mt-1 {
        margin-top: 15px;
    }

    .price-table {
        padding: 50px 30px;
        border-radius: 7px;
        overflow: hidden;
        position: relative;
        background: #fff;
        text-align: center;
    }

    .price-header {
        position: relative;
        z-index: 9;
    }

    .list-unstyled {
        padding-left: 0;
        list-style: none;
    }

    .price-list ul li {
        position: relative;
        display: block;
        margin-bottom: 20px;
    }

    .price-list li i {
        color: #3143ef;
        line-height: 30px;
        font-size: 14px;
        width: 30px;
        height: 30px;
        background: rgba(49, 67, 239, .1);
        display: inline-block;
        text-align: center;
        border-radius: 50%;
        margin-right: 10px;
    }

    .price-value {
        display: inline-block;
        margin: 20px 0;
    }

    .price-value h2 {
        font-size: 50px;
        line-height: 50px;
        font-weight: 600;
        color: #3143ef;
        margin-bottom: 0;
        position: relative;
        display: inline-block;
    }

    .price-value h2 span {
        font-weight: 400;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .btn {
        background: #3143ef;
        color: #fff;
        padding: 12px 20px;
        position: relative;
        display: inline-block;
        cursor: pointer;
        outline: none;
        border: 0;
        vertical-align: middle;
        text-decoration: none;
    }
</style>
@endsection
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
                <li class="active">Plan Create</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-2 pull-right">
                    <a href="{{route('plan.index')}}" class="btn btn-info">Back</a>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2 class="title">Update Pricing Plan</h2><br>
                        <h2 class="title text-info">{{$plan->name}}</h2>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-8 col-md-offset-2">
                    <form action="{{route('plan.update', $plan->id)}}" method="post">
                        @csrf @method('put')
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="name">Package Name: </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name" value="{{ old('name')??$plan->name }}"
                                        placeholder="Enter Package Name" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('name')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label">Description: </label>
                                <div class="col-sm-9">
                                    <textarea name="description" id="description"
                                        class="form-control col-xs-10 col-sm-8" style="width: 365px; height: 50px;"
                                        cols="15" rows="5"
                                        placeholder="Enter Description">{{ old('description')??$plan->description }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('description')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="amount">Amount/Price:</label>
                                <div class="col-sm-9">
                                    <input step="any" type="number" name="amount" id="amount" value="{{old('amount')??$plan->amount}}"
                                        placeholder="Enter Amount/Price" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('amount')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="interval">Interval (day):</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="interval" id="interval"
                                        value="{{old('interval')??$plan->interval}}" placeholder="Enter Interval (day)" autofocus
                                        required class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('interval')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="sales_quotation">Sales Quotation:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="sales_quotation" id="sales_quotation"
                                        value="{{old('sales_quotation')??$plan->sales_quotation}}" placeholder="Enter Sales Quotation" autofocus
                                        required class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('sales_quotation')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="purchase_quotation">Purchase
                                    Quotation:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="purchase_quotation" id="purchase_quotation"
                                        value="{{old('purchase_quotation')??$plan->purchase_quotation}}" placeholder="Enter Purchase Quotation"
                                        autofocus required class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('purchase_quotation')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="invoice">Sales Invoice:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="invoice" id="invoice" value="{{old('invoice')??$plan->invoice}}"
                                        placeholder="Enter Sales Invoice" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('invoice')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="bill">Purcase invoice:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="bill" id="bill" value="{{old('bill')??$plan->bill}}"
                                        placeholder="Enter Purcase invoice" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('bill')}}</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="receipt">Sales receipts:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="receipt" id="receipt" value="{{old('receipt')??$plan->receipt}}"
                                        placeholder="Enter Sales receipts" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('receipt')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="payment">Payment receipts:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="payment" id="payment" value="{{old('payment')??$plan->payment}}"
                                        placeholder="Enter Payment receipts" autofocus required
                                        class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('payment')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="payslip">Payslip:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="payslip" id="payslip" value="{{old('payslip')??$plan->payslip}}"
                                        placeholder="Enter Payslip" autofocus required class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('payslip')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-3 control-label" for="discount">Discount with focus taxation for
                                    tax & Accounting fee (%).:</label>
                                <div class="col-sm-9">
                                    <input step="1" type="number" name="discount" id="discount"
                                        value="{{old('discount')??$plan->discount}}"
                                        placeholder="Discount with focus taxation for tax & Accounting fee (%)."
                                        autofocus required class="col-xs-10 col-sm-8" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('discount')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <label class="control-label" for="access_report">
                                        <input type="checkbox" {{$plan->access_report==1?'checked':''}} name="access_report" id="access_report" value="1">
                                        Access all payroll and Invoice and purchase related reports.
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('access_report')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <label class="control-label" for="customer_support">
                                        <input type="checkbox" name="customer_support" id="customer_support"  {{$plan->customer_support==1?'checked':''}} value="1">
                                        24/7 Customer support.
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 control-label"></label>
                                <span class="text-danger col-sm-9"> {{$errors->first('customer_support')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- <div class="row">
                @forelse ($plans as $plan)
                <div class="col-lg-4 col-md-12  d-flex">
                    <div class="price-table box-shadow">
                        <div class="price-header">
                            <h4 class="price-title"><u>{{ $plan->name }}</u></h4>
                            <p>{{ $plan->description }}</p>
                        </div>
                        <div class="price-list">
                            <ul class="list-unstyled" align="left">
                                @if (!empty($plan->sales_quotation))
                                <li><i class="fa fa-check"></i>{{ $plan->sales_quotation }} Sales Quotation</li>
                                @endif
                                @if (!empty($plan->purchase_quotation))
                                <li><i class="fa fa-check"></i>{{ $plan->purchase_quotation }} Purchase
                                    Quotation</li>
                                @endif
                                @if (!empty($plan->invoice))
                                <li><i class="fa fa-check"></i>{{ $plan->invoice }} Sales Invoice</li>
                                @endif
                                @if (!empty($plan->bill))
                                <li><i class="fa fa-check"></i>{{ $plan->bill }} Purcase invoice</li>
                                @endif
                                @if (!empty($plan->receipt))
                                <li><i class="fa fa-check"></i>{{ $plan->receipt }} Sales receipts</li>
                                @endif
                                @if (!empty($plan->payment))
                                <li><i class="fa fa-check"></i>{{ $plan->payment }} Payment receipts</li>
                                @endif
                                @if (!empty($plan->payslip))
                                <li><i class="fa fa-check"></i>{{ $plan->payslip }} Payslip</li>
                                @endif
                                @if (!empty($plan->discount))
                                <li><i class="fa fa-check"></i>{{ $plan->discount }}% discount with focus
                                    taxation for tax &amp;
                                    Accounting fee.</li>
                                @endif
                                @if (!empty($plan->access_report))
                                <li><i class="fa fa-check"></i>Access all payroll and Invoice and purchase
                                    related
                                    reports</li>
                                @endif
                                @if (!empty($plan->customer_support))
                                <li><i class="fa fa-check"></i>24/7 Customer support</li>
                                @endif
                            </ul>
                        </div>
                        <div class="price-value">
                            <h2>
                                <span class="price-dollar">$</span>{{number_format($plan->amount, 2)}}<span
                                    style="font-size: 13px;">/ {{$plan->interval}}
                                    Days</span>
                            </h2>
                        </div>
                        <a class="btn btn-theme btn-block mt-4 " href="#pak"> <span>Get It Now</span>
                        </a>
                    </div>
                </div>
                @empty
                @endforelse
            </div> --}}
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->


<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
            
        });
</script>
@endsection
