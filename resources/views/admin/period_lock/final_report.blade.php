@extends('admin.layout.master')
@section('title','Client')
@section('content')



<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>Tools</li>
                    <li class="active">Verify Account</li>
                    <li class="active">General Ledger Report</li>
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
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div align="center">
                        <strong style="font-size:18px;"> Mohamed Shieta</strong><br />                    
                        From Date : 30/01/2020 To : 01/04/2020
                    </div>
                    
                    <table class="table table-bordered">
                        <head>
                            <tr>
                                <th>Date</th>
                                <th>Transation Id</th>
                                <th>Total Debit</th>
                                <th>Total Credit</th>
                                <th>Difference Amount</th>
                            </tr>
                        </head>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>                    
                        </tbody>
                    </table>  
                    
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- inline scripts related to this page -->

@endsection