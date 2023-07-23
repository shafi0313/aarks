@extends('frontend.layout.master')
@section('title','Prepare Budgets')
@section('content')
<?php $p="prb"; $mp="acccounts";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading py-2">
                        <h3>Prepare Budgets</h3>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{route('client-budget.create')}}" class="was-validated" method="get" autocomplete="off"
                            autocapitalize="off">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="select-profession">
                                            <h2>Select Profession</h2>
                                        </label>
                                        <select required class="form-control" id="profession_id" name="profession_id">
                                            <option value selected> Select a Profession</option>
                                        </select>
                                        {{-- <div id="profession-container"></div> --}}
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            <h2>Budget Year Date</h2>
                                        </label>
                                        <input required type="test" id="date" name="date"
                                            class="form-control date-picker datepicker" data-date-format="dd/mm/yyyy"
                                            Placeholder="DD/MM/YYYY" />
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Show Report</button>
                                </div>
                            </div><!-- /.row -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // $(".datepicker").datepicker({
    //     dateFormat: "dd/mm/yy",
    //     changeMonth: true,
    //     changeYear: true
    // });
    $('#profession_id').select2({
        ajax: {
            url           : '{{route("select-two")}}',
            type          : 'get',
            dataType      : 'json',
            delay         : 250,
            cache         : true,
            data    : function(params) {
                return {
                    q        : $.trim(params.term),
                    client_id: {{client()->id}},
                    type     : 'getProfession'
                };
            },
            processResults: function (data) {
                return {
                    results : data
                };
            },
        }
    });
</script>
@endsection
