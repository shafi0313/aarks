@extends('frontend.layout.master')
@section('title', 'Dashboard')
@section('content')
    <?php $p = 'index';
    $mp = 'index'; ?>
    @include('frontend.dashboard_css')
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">
                                Welcome to AARKS :
                                @if (auth()->guard('client')->check())
                                    {{ client()->email }}
                                @endif
                            </h3>
                        </div>
                    </div>
                    <div class="pb-4">
                        @if (auth()->guard('client')->check())
                            <div class="row">
                                <div class="col-md-12 my-3">
                                    <a href="{{ route('calendar.home') }}" class="btn btn-primary">Calendar</a>
                                </div>
                                <div class="col-xl-3 mb-50">
                                    <div
                                        class="gradient-style4 text-white box-shadow border-radius-10 height-100-p widget-style3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="widget-data">
                                                <div class="weight-400 font-20">Professions</div>
                                                <div class="weight-300 font-30">{{ $profession }}</div>
                                            </div>
                                            <div class="widget-icon">
                                                <div class="icon"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-50">
                                    <div
                                        class="gradient-style1 text-white box-shadow border-radius-10 height-100-p widget-style3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="widget-data">
                                                <div class="weight-400 font-20">Customers</div>
                                                <div class="weight-300 font-30">{{ $customer }}</div>
                                            </div>
                                            <div class="widget-icon">
                                                <div class="icon"><i class="fa-solid fa-users"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-50">
                                    <div
                                        class="gradient-style2 text-white box-shadow border-radius-10 height-100-p widget-style3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="widget-data">
                                                <div class="weight-400 font-20">Suppliers</div>
                                                <div class="weight-300 font-30">{{ $supplier }}</div>
                                            </div>
                                            <div class="widget-icon">
                                                <div class="icon"><i class="fa-solid fa-user-group"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-50">
                                    <div
                                        class="gradient-style3 text-white box-shadow border-radius-10 height-100-p widget-style3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="widget-data">
                                                <div class="weight-400 font-20">Recurring</div>
                                                <div class="weight-300 font-30">{{ $recurring }}</div>
                                            </div>
                                            <div class="widget-icon">
                                                <div class="icon"><i class="fa-solid fa-arrows-rotate"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                            <tr class="bg-warning">
                                                <th colspan="4" class="text-center" style="font-size: 20px">Periods</th>
                                            </tr>
                                            <tr class="text-center bg-primary text-white">
                                                <th>SN</th>
                                                <th>Financial Year</th>
                                                <th>Period From Date</th>
                                                <th>Period to Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($periods as $group)
                                                @php $i = 1; @endphp
                                                <tr class="bg-info text-white">
                                                    <th colspan="4">Profession: {{ $group->first()->profession->name }}
                                                    </th>
                                                </tr>
                                                @foreach ($group as $period)
                                                    <tr class="text-center">
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $period->year }}</td>
                                                        <td>{{ bdDate($period->start_date) }}
                                                        </td>
                                                        <td>{{ bdDate($period->end_date) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @isset($periodLock->date)
                                    <div class="col-xl-4 mb-50">
                                        <div class="gradient-style3 text-white box-shadow border-radius-10 widget-style3">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="widget-data">
                                                    <div class="weight-400 font-20">Period lock as at:</div>
                                                    <div class="weight-300 font-30">{{ $periodLock->date }}</div>
                                                </div>
                                                <div class="widget-icon">
                                                    <div class="icon"><i class="fa-solid fa-lock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endisset
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->
    <script></script>
@stop
