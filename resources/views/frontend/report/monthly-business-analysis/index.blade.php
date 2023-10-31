@extends('frontend.layout.master')
@section('title', 'Monthly business analysis')
@section('content')
    <?php $p = 'avdbudget';
    $mp = 'avdr'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading py-2">
                            <h3>Monthly business analysis</h3>
                        </div>
                        <div class="card-body">
                            <div class="page-content" style="margin-top: 20px;">
                                <form action="{{ route('client-avd.monthly_business_analysis.report') }}" class="was-validated" method="get"
                                    autocomplete="off" autocapitalize="off">
                                    <div class="row justify-content-center">
                                        <input type="hidden" name="client_id" value="{{ client()->id }}">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="select-profession">Select Profession</label>
                                                <select class="form-control" id="profession_id" onchange="getDates()"
                                                    name="profession_id" required>
                                                    <option> Select a Profession</option>
                                                </select>
                                                {{-- <div id="profession-container"></div> --}}
                                            </div>

                                            <div class="form-group">
                                                <label for="year">Select Financial Year</label>
                                                <select required class="form-control" id="year"  name="year"
                                                    required>
                                                    <option value=""> Select a Financial Year</option>
                                                    {{-- Last 10 years --}}
                                                    @for ($i = date("Y",strtotime("+5 years")); $i >= date("Y"); $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                {{-- <div id="year-container"></div> --}}
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
                url: '{{ route('select-two') }}',
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function(params) {
                    return {
                        q: $.trim(params.term),
                        client_id: {{ client()->id }},
                        type: 'getProfession'
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            }
        });
    </script>
@endsection
