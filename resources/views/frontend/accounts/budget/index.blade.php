@extends('frontend.layout.master')
@section('title', 'Prepare Budget/Budget Entry')
@section('content')
    <?php $p = 'prb';
    $mp = 'acccounts'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading py-2">
                            <h3>Prepare Budget/Budget Entry</h3>
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
                            <form action="{{ route('client-budget.create') }}" class="was-validated" method="get"
                                autocomplete="off" autocapitalize="off">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="select-profession">
                                                <h4>Select Profession</h4>
                                            </label>
                                            <select required class="form-control" id="profession_id" name="profession_id">
                                                <option value selected> Select a Profession</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                <h4>Budget Year</h4>
                                            </label>
                                            <select required class="form-control" id="year" name="year" required>
                                                <option value=""> Select a Financial Year</option>
                                                {{-- Last 10 years --}}
                                                @for ($i = date('Y', strtotime('+5 years')); $i >= date('Y'); $i--)
                                                    <option value="{{ $i }}">{{ $i + 1 }}</option>
                                                @endfor
                                            </select>
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
