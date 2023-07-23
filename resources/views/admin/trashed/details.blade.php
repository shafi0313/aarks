@extends('admin.layout.master')
@section('title', 'Trashed Details')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li><i class="ace-icon fa fa-trash trash-icon"></i> Trash</li>
                    <li class="">{{$client->full_name}}</li>
                    <li class="">{{$src}}</li>
                    <li class="active">Details</li>
                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        @if ($src == "INV")
                            @include('admin.trashed.source.inv', ['invoices'=>$ledgers])
                        @endif
                        @if ($src == "PIN")
                            @include('admin.trashed.source.pin', ['payments'=>$ledgers])
                        @endif
                        @if ($src == "PBP")
                            @include('admin.trashed.source.pbp', ['services'=>$ledgers])
                        @endif
                        @if ($src == "PBN")
                            @include('admin.trashed.source.pbn', ['payments'=>$ledgers])
                        @endif
                        @if ($src == "CUSTOMER")
                            @include('admin.trashed.source.customer', ['customers'=>$ledgers])
                        @endif
                        @if ($src == "JNP")
                            @include('admin.trashed.source.jnp', ['journals'=>$ledgers])
                        @endif
                        @if ($src == "CBE")
                            @include('admin.trashed.source.cbe', ['cashbooks'=>$ledgers])
                        @endif
                        @if ($src == "INP")
                            @include('admin.trashed.source.inp', ['inputs'=>$ledgers])
                        @endif
                        @if ($src == "BST")
                            @include('admin.trashed.source.bst', ['imports'=>$ledgers])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(function(){
    $('#d-table').DataTable();
    $('#dataTable').DataTable();
    })
</script>
@endsection
