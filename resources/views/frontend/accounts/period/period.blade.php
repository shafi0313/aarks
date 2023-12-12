@extends('frontend.layout.master')
@section('title', 'Add Edit Period')
@section('content')
    <?php $p = 'aep';
    $mp = 'acccounts'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="table-header" style="margin-bottom: 0px;">
                        <button class="btn btn-danger" data-toggle="collapse" data-target="#demo"
                            style="float: right !important;line-height: 16px;">
                            <span>
                                <i class="fa fa-plus"></i>
                                Add Period
                            </span>
                        </button>
                    </div>

                    <div id="demo" class="collapse" style="padding: 30px; border:1px solid #307ECC">
                        @if (!empty($errors->any()))
                            <small class="text-danger">* {{ $errors->first() }}</small>
                        @endif
                        <form class="form-inline" action="{{ route('client.periodStore') }}" method="POST"
                            autocomplete="off">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Financial Year <strong style="color:red;">*</strong></label>
                                        <input id="financial-year" type="number" name="year" class="form-control"
                                            length="4" placeholder="Year" value="{{ old('year') }}"><br>
                                        <small id="msg" style="display: none;color: red">HEllo</small>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">Start Month <strong style="color:red;">*</strong></label>
                                        <input type="" data-date-format="dd/mm/yyyy" class="form-control datepicker"
                                            name="start_date" placeholder="Start Time" value="{{ old('start_date') }}">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="usr">End Month <strong style="color:red;">*</strong></label>
                                        <input type="" data-date-format="dd/mm/yyyy" class="form-control datepicker"
                                            name="end_date" placeholder="End Time" id="pwd"
                                            value="{{ old('end_date') }}">

                                    </div>
                                </div>
                                <br>
                                <div class="col-md-1" style="margin-top: 22px">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <style>
                        .table thead tr th {
                            padding: 8px;
                        }

                        .table tbody tr td {
                            line-height: 30px !important;
                        }
                    </style>
                    <table id="example" class="table table-sm table-striped table-bordered table-hover display">
                        <thead>
                            <tr class="text-center">
                                <th>SN</th>
                                <th>Financial Year</th>
                                <th>Period From Date</th>
                                <th>Period to Date</th>
                                <th class="text-center no-sort">Action</th>
                                <th class="text-center no-sort">Add Data</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($periods as $period)
                                @php
                                    $dStores = \App\Models\Data_storage::where('period_id', $period->id)->get();
                                @endphp
                                <tr class="text-center">
                                    <td>{{ @$i += 1 }}</td>
                                    <td>{{ $period->year }}</td>
                                    <td>{{ bdDate($period->start_date) }}</td>
                                    <td>{{ bdDate($period->end_date) }}</td>
                                    @if (($period->can_delete == 1) & ($dStores->count() <= 0))
                                        <td class="text-center">
                                            <form action="{{ route('client.periodDelete', $period->id) }}" method='post'>
                                                @csrf @method('delete')
                                                <button title="Period Delete" type="submit" class=" btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-outline-success"
                                            href="{{ route('client.periodAddEdit', [$client->id, $profession->id, $period->id]) }}">Add/Edit
                                            Data</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->
    @include('frontend.layout.includes.data_table_js')

    <script>
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        })

        $(".datepicker").datepicker({
            // dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            autoclose: true,
            todayHighlight: true
        });

        $('#financial-year').keyup(function() {
            let year = $(this).val();
            if (year.length == 4) {
                $('#msg').show().html('Your selected financial year july ' + (year - 1) + ' to June ' + year);
            } else {
                $('#msg').hide();
            }

        });
    </script>

@stop
