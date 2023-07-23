@extends('admin.layout.master')
@section('title', 'Plan List')
@section('style')
<style>
    .price-table {
        padding: 50px 30px;
        border-radius: 7px;
        overflow: hidden;
        position: relative;
        background: #8aa7bb36;
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
                <li class="active">Plan List</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-2 pull-right">
                    <a href="{{route('plan.create')}}" class="btn btn-primary">Create</a>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2 class="title">Our Pricing</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($plans as $plan)
                <div class="col-lg-4 col-md-12  d-flex">
                    <div class="price-table box-shadow">
                        <div class="d-flex" style="display: flex;justify-content: space-between;align-items: center;">
                            <div class="">
                                <a href="{{route('plan.edit', $plan->id)}}" class="btn btn-info">Edit</a>
                            </div>
                            <div class="">
                                <form action="{{route('plan.destroy', $plan->id)}}" method="post"
                                    enctype="multipart/form-data" role="form">
                                    @csrf
                                    <button onclick="return confirm('Are you sure you want to delete this plan?')"  type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
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
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->


<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
            
        });
</script>
@endsection
