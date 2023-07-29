@extends('admin.layout.master')
@section('title', 'Bank Reconcilation')
@section('style')
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
                    <li>Report</li>
                    <li class="active">Bank Reconcilation</li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="glyphicon glyphicon-align-justify"></i>
                                            {{ $client->fullname }}
                                        </h3>
                                    </div>
                                    <div align="center">
                                        <h1>{{ $client->fullname }}</h1>
                                        <h2>Bank Reconcilation</h2>
                                        <h4><u> From Date : {{ $data['from_date'] }} To {{ $data['to_date'] }}</u></h4>
                                    </div>
                                    <div class="table table-responsive">
                                        <form action="{{ route('bank_recon.store', $client->id) }}" method="post">
                                            @csrf
                                            {{-- <input type="hidden" name="client_id" value="{{$client->id}}"> --}}
                                            {{-- <input type="hidden" name="diff_array" value="" id="diff_arr_inp"> --}}
                                            <div class="pull-right py-5" style="padding: 15px 0">
                                                @php
                                                $is_disabled = $bank_recons->count() <= 0 ? 'disabled' : ''; @endphp <input {{ $is_disabled }} type="submit" name="type"
                                                    value="Print/PDF" class="btn"
                                                    style="background: blue !important; border-color: blue !important;">
                                                <input {{ $is_disabled }} type="submit" name="type"
                                                    value="Export to excel" class="btn"
                                                    style="background: blue !important; border-color: blue !important">
                                                @if (optional($bank_recons->first())->is_posted == 0)
                                                    @if ($posted_recons->count() > 0)
                                                        <input disabled type="submit" name="type" value="Save"
                                                            class="btn"
                                                            style="background: green !important; border-color: green !important">
                                                    @else
                                                        <input type="submit" name="type" value="Save" class="btn"
                                                            style="background: green !important; border-color: green !important">
                                                    @endif
                                                    <input {{ $is_disabled }} type="submit" name="type" value="Post"
                                                        class="btn btn-warning">
                                                @endif
                                                @if ($posted_recons->count() > 0)
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#permisiion">
                                                        Edit With Permission
                                                    </button>
                                                @else
                                                    <button disabled type="button" class="btn btn-danger"
                                                        data-toggle="modal" data-target="#permisiion">
                                                        Edit With Permission
                                                    </button>
                                                @endif
                                            </div>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Tran ID</th>
                                                        <th>Account Code</th>
                                                        <th>Narration</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <script>
                                                        var diff_array = [];
                                                    </script>
                                                    @php
                                                        $totalDebit = $totalCredit = $totalDiff = 0;
                                                    @endphp
                                                    @forelse ($recons as $i => $recon)
                                                        @php
                                                            $debit = $credit = $diff = 0;
                                                            $date   = \Carbon\Carbon::parse($recon['date'])->addDay();
                                                            
                                                            $debit  = abs($recon['debit']);
                                                            $credit = abs($recon['credit']);
                                                            if ($recon['debit'] < 0) {
                                                                $debit = 0;
                                                                $credit = abs($recon['debit']);
                                                            }
                                                            if ($recon['credit'] < 0) {
                                                                $credit = 0;
                                                                $debit  = abs($recon['credit']);
                                                            }
                                                            $diff       = abs($debit - $credit);
                                                            $identifier = $recon['tran_id'] . $recon['code'] . abs_number($diff, 2, '_') . $date->format('Ymd');
                                                        @endphp
                                                        @if (!in_array($identifier, $posted_identify))
                                                            @php
                                                                
                                                                $totalDebit  += $debit;
                                                                $totalCredit += $credit;
                                                                $totalDiff   += $diff;
                                                                
                                                            @endphp
                                                            <script>
                                                                diff_array[{{ $i }}] = 0;
                                                            </script>
                                                            <input type="hidden" name="date[]"
                                                                value="{{ $date->format('Y-m-d') }}">
                                                            <input type="hidden" name="tran_id[]"
                                                                value="{{ $recon['tran_id'] }}">
                                                            <input type="hidden" name="code_name[]"
                                                                value="{{ $recon['name'] }}">
                                                            <input type="hidden" name="chart_id[]"
                                                                value="{{ $recon['code'] }}">
                                                            <input type="hidden" name="narration[]"
                                                                value="{{ $recon['narration'] }}">
                                                            <input type="hidden" name="debit[]"
                                                                value="{{ $recon['debit'] }}">
                                                            <input type="hidden" name="credit[]"
                                                                value="{{ $recon['credit'] }}">
                                                            <input type="hidden" name="identifier[]"
                                                                value="{{ $identifier }}">
                                                            <input type="hidden" name="diff_array[]"
                                                                value="{{ in_array($identifier, $bank_recons->pluck('identifier')->toArray())
                                                                    ? $bank_recons->where('identifier', $identifier)->first()->diff
                                                                    : 0 }}"
                                                                id="diff_{{ $identifier }}">
                                                            <tr>
                                                                <td>{{ $date->format('d/m/Y') }}</td>
                                                                <td>{{ $recon['tran_id'] }}</td>
                                                                <td>
                                                                    {{ $recon['name'] }}
                                                                    {{-- <br>
                                                        {{$identifier}} --}}
                                                                </td>
                                                                <td>{{ $recon['narration'] }}</td>
                                                                <td class="text-right">{{ nFA2($debit) }}</td>
                                                                <td class="text-right">{{ nFA2($credit) }}</td>
                                                                <td>
                                                                    <input type="checkbox" name="diff[]"
                                                                        data-id="{{ $i }}"
                                                                        data-identifier="{{ $identifier }}"
                                                                        id="diff_{{ $i }}"
                                                                        value="{{ $debit != 0 ? -$debit : $credit }}"
                                                                        {{ in_array($identifier, $bank_recons->pluck('identifier')->toArray()) ? 'checked' : '' }}>
                                                                    &nbsp;
                                                                    <label for="diff_{{ $i }}">
                                                                        {{ nFA2($diff) }}
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No Data Found</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr>
                                                        <td colspan="4">Total:</td>
                                                        <td class="text-right">{{ nFA2($totalCredit) }}</td>
                                                        <td class="text-right">{{ nFA2($totalDebit) }}</td>
                                                        @if (empty($bank_recons))
                                                            <td class="text-right">
                                                                <span id="total_diff">
                                                                    {{ nFA2($totalDiff) }}                                                                    
                                                                </span>
                                                            </td>
                                                        @else
                                                            <td class="text-right">
                                                                <span id="total_diff">
                                                                    {{ number_format($bank_recons->sum('diff'), 2) }}
                                                                </span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="100%">
                                                            <div class="pull-right py-5" style="padding: 15px 0">
                                                                @php
                                                                $is_disabled = $bank_recons->count() <= 0 ? 'disabled' : ''; @endphp <input {{ $is_disabled }} type="submit"
                                                                    name="type" value="Print/PDF" class="btn"
                                                                    style="background: blue !important; border-color: blue !important;">
                                                                <input {{ $is_disabled }} type="submit" name="type"
                                                                    value="Export to excel" class="btn"
                                                                    style="background: blue !important; border-color: blue !important">
                                                                @if (optional($bank_recons->first())->is_posted == 0)
                                                                    @if ($posted_recons->count() > 0)
                                                                        <input disabled type="submit" name="type"
                                                                            value="Save" class="btn"
                                                                            style="background: green !important; border-color: green !important">
                                                                    @else
                                                                        <input type="submit" name="type"
                                                                            value="Save" class="btn"
                                                                            style="background: green !important; border-color: green !important">
                                                                    @endif
                                                                    <input {{ $is_disabled }} type="submit"
                                                                        name="type" value="Post"
                                                                        class="btn btn-warning">
                                                                @endif
                                                                @if ($posted_recons->count() > 0)
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-toggle="modal" data-target="#permisiion">
                                                                        Edit With Permission
                                                                    </button>
                                                                @else
                                                                    <button disabled type="button" class="btn btn-danger"
                                                                        data-toggle="modal" data-target="#permisiion">
                                                                        Edit With Permission
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->

            <!-- Modal -->
            <div class="modal fade" id="permisiion" tabindex="-1" role="dialog" aria-labelledby="permisiionLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="permisiionLabel">Enter Admin Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('bank_recon.permission', $client->id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="password">Admin Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('input[type="checkbox"]').on('change', function() {
                var i = $(this).data('id');
                var identifier = $(this).data('identifier');
                var totalDiff = 0;
                var diff = $("#diff_" + identifier);
                if ($(this).is(':checked')) {
                    console.log(diff)
                    diff_array[i] = parseFloat($(this).val()).toFixed(2);
                    diff.val(parseFloat($(this).val()).toFixed(2));
                } else {
                    diff_array[i] = 0.00;
                    diff.val(0.00);
                }
                $('input[type="checkbox"]:checked').each(function() {
                    totalDiff += parseFloat($(this).val());
                });
                $('#total_diff').text(totalDiff.toFixed(2));
                $('#diff_arr_inp').val(diff_array);
            });
        });

        // $(document).ready(function() {
            // var diff_array = []; // Assuming this array is defined somewhere in your code.

            // $('input[type="checkbox"]').on('change', function() {
            //     var i = $(this).data('id');
            //     var identifier = $(this).data('identifier');
            //     var totalDiff = 0;
            //     var diff = $("#diff_" + identifier);

            //     if ($(this).is(':checked')) {
            //         diff_array[i] = parseFloat($(this).val()).toFixed(2);
            //         diff.val(parseFloat($(this).val()).toFixed(2));
            //     } else {
            //         diff_array[i] = 0.00;
            //         diff.val(0.00);
            //     }

            //     $('input[type="checkbox"]:checked').each(function() {
            //         totalDiff += parseFloat($(this).val());
            //     });

            //     $('#total_diff').text(totalDiff.toFixed(2));
            //     $('#diff_arr_inp').val(JSON.stringify(diff_array)); // Convert array to JSON string
            // });
        // });
    </script>
@endsection
