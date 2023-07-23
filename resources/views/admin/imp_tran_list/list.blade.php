@extends('admin.layout.master')
@section('title','Important Transaction List')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">Add/Edit Data</a>
                    </li>
                    <li>
                        <a href="#">{{$client->first_name}} {{$client->first_name}}</a>
                    </li>
                    <li class="active">{{$profession->name}}</li>
                    <li>Import BankStatement List</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
                    </form>
                </div><!-- /.nav-search -->
            </div>

            <div class="page-content">

                <!-- Settings -->
{{--            @include('admin.layout.settings')--}}
            <!-- /Settings -->

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <table class="table table-bordered">
                            <thead>
                                <th class="center">Date</th>
                                <th class="center">Transation Id</th>
                                <th class="center">Total Debit</th>
                                <th class="center">Total Credit</th>
                                <th class="center">Difference Amount</th>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td class="center">{{ $transaction->date->format(aarks('frontend_date_format')) }}</td>
                                        <td class="center">
                                            <a href="{{route('bs_input.tranListView',$transaction->transaction_id)}} " class=" text-success">
                                                {{ $transaction->transaction_id }}
                                            </a>
                                        </td>
                                        <td class="center">{{ $debit}}</td>
                                        <td class="center">{{ $credit }}</td>
                                        <td class="center">{{$debit-$credit }}</td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="text-center"><h4>No Data Found</h4></td>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $transactions->links() }}
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Modal -->
@endsection
