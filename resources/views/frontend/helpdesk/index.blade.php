@extends('frontend.layout.master')
@section('title', 'Support for '. $helpdesk->name)
@section('content')
    <?php $p = $helpdesk->slug;
    $mp = 'help'; ?>
<style>
    a, a:hover, a:focus, a:active {
        text-decoration: none;
        outline: none;
    }
    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .faq {
        padding: 100px  0px;
        background: #f7fbff;
    }

    .faq .accordion .card {
        border: none;
    }

    .faq .accordion .card:not(:first-of-type) .card-header:first-child {
        border-radius: 10px;
    }

    .faq .accordion .card .card-header {
        border: none;
        border-radius: 10px;
        padding: 0;
    }

    .faq .accordion .card .card-header h5 {
        padding: 0;
    }

    .faq .accordion .card .card-header h5 button {
        color: #1e3056;
        font-size: 18px;
        font-weight: 500;
        text-decoration: none;
        padding: 0 30px 0 70px;
        height: 80px;
        display: block;
        width: 100%;
        color: rgba(30, 48, 86, 0.8);
        text-align: left;
        background: #fff;
        -webkit-box-shadow: 0px -50px 140px 0px rgba(69, 81, 100, 0.1);
        box-shadow: 0px -50px 140px 0px rgba(69, 81, 100, 0.1);
        border-radius: 10px 10px 0 0;
        position: relative;
    }

    .faq .accordion .card .card-header h5 button:after {
        font-family: 'FontAwesome';
        position: absolute;
        left: 30px;
        top: 50%;
        margin-top: -10px;
        width: 20px;
        height: 20px;
        background-color: transparent;
        color: #ff5f74;
        text-align: center;
        border: 1px solid #ff5f74;
        border-radius: 50%;
        line-height: 100%;
        content: '\f06e';
        font-size: 10px;
        line-height: 18px;
        font-weight: 900;
    }

    .faq .accordion .card .card-header h5 button.collapsed {
        background: #fff;
        border-radius: 10px;
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid rgba(97, 125, 255, 0.2);
    }

    .faq .accordion .card .card-header h5 button[aria-expanded="true"]:after {
        content: '\f068';
        color: #fff;
        background-image: -webkit-linear-gradient(-180deg, #5e7eff 0%, #ff5f74 30%, #a85fff 100%);
    }

    .faq .accordion .card .card-body {
        -webkit-box-shadow: 0px 15px 140px 0px rgba(69, 81, 100, 0.1);
        box-shadow: 0px 15px 140px 0px rgba(69, 81, 100, 0.1);
        border-radius: 0 0 10px 10px;
        padding-top: 0;
        margin-top: -6px;
        padding-left: 72px;
        padding-right: 70px;
        padding-bottom: 23px;
        color: rgba(30, 48, 86, 0.8);
        line-height: 30px;
    }
</style>
<section class="faq page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if ($helpdesk->title)
                    <h1 class="text-center">{{ ucfirst($helpdesk->title) }}</h1>
                @endif
                @if ($helpdesk->thumbnail)
                    <img src="{{ asset($helpdesk->thumbnail) }}" alt="..." class="img-fluid"> <br> <br>
                @endif
                {!! $helpdesk->description !!}
            </div>
        </div>
    </div>
</section>
@stop
