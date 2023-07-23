@extends('frontend.layout.master')
@section('title','Income & Expense Comparison')
@section('content')
<?php $p="avdbudget"; $mp="avdr";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-heading py-2">
                        <h3>Income & Expense Comparison</h3>
                    </div>
                    <div class="card-body">
                        <div class="page-content" style="margin-top: 20px;">
                            <form action="{{route('client-avd.income_expense_comparison.report')}}" class="was-validated" method="get"
                                autocomplete="off" autocapitalize="off">
                                <div class="row justify-content-center">
                                    <input type="hidden" name="client_id" value="{{client()->id}}">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="select-profession">
                                                <h2>Select Profession</h2>
                                            </label>
                                            <select class="form-control" id="profession_id" onchange="getDates()"
                                                name="profession_id" required>
                                                <option> Select a Profession</option>
                                            </select>
                                            {{-- <div id="profession-container"></div> --}}
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <h2>Date</h2>
                                            </label>
                                            <table class="table table-bordered table-hover">
                                                <tbody id="date-container"></tbody>
                                            </table>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Show Report</button>
                                    </div>
                                </div><!-- /.row -->
                            </form>
                        </div><!-- /.page-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
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
    function getDates() {
        $.ajax({
            url: '{{route("select-two")}}',
            type: 'get',
            dataType: 'json',
            data: {
                client_id: {{client()->id}},
                profession_id: $('#profession_id').val(),
                type: 'getFinancialYear'
            },
            success: function (res) {
                $('#date-container').html(res.tr);
            }
        });
    }
</script>
@endsection
