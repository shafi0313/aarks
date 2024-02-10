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
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Client Name</th>
                                                <th>Package Name</th>
                                                <th>Total Amount</th>
                                                <th>Time Duration</th>
                                                <th>Message</th>
                                                <th>Payment RCPT</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($paylists as $paylist)
                                                <tr>
                                                    <td>{{ $paylist->created_at->format('d/m/Y') }}</td>
                                                    <td>{{ $paylist->client->fullname }}</td>
                                                    <td>
                                                        {{ $paylist->subscription->name }}
                                                    </td>
                                                    <td>$ {{ number_format($paylist->amount, 2) }}</td>
                                                    <td>
                                                        {{ $paylist->duration }}
                                                    </td>
                                                    <td>{{ $paylist->message }}</td>
                                                    <td>
                                                        <a href="{{ asset($paylist->rcpt) }}" download>Download</a>
                                                    </td>
                                                    <td>
                                                        <a title="Client Payment Approve"
                                                            href="{{ route('client_payment_pending_details', $paylist->id) }}">
                                                            @if ($paylist->status == 0)
                                                                Pending
                                                            @elseif($paylist->status == 1)
                                                                Approved
                                                            @else
                                                                Expired
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons"
                                                            style="display: flex; justify-content: center; font-size: 16px">
                                                            <a title="Client Payment Approve" class="green"
                                                                href="{{ route('client_payment_pending_details', $paylist->id) }}"
                                                                style="margin-right: 10px">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                            <a title="Client Payment Edit" class="green"
                                                                href="{{ route('client_payment_edit', $paylist->id) }}">
                                                                <i class="ace-icon fa fa-pencil"></i>
                                                            </a>
                                                            <form onsubmit="return confirm('Are You Sure?')"
                                                                action="{{ route('client_payment_delete', $paylist->id) }}"
                                                                method="post">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="red delete"
                                                                    style="border: none; background: transparent; margin-left: 10px">
                                                                    <i class="ace-icon fa fa-trash-o"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="9">
                                                        <h1 class="text-center text-info">EMPTY TABLE</h1>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

@endsection
