<!DOCTYPE html>
<html lang="en">
@php
    $print = 1;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>General Ledgers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    @include('frontend.print-css')
</head>
<style>
    body {
        font-size: 12px;
    }
</style>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h2 class="text-center bolder">{{ clientName($client) }}</h2>
                <h5 class="text-center">
                    <u>Ledger Report From: {{ $start_date->format('d/m/Y') }} to :
                        {{ $end_date->format('d/m/Y') }}</u>
                </h5>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.reports.general_ledger.table', [
                            'url' => 'general_ledger.transaction',
                        ])
                    </div>
                </div>
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.Container -->

</body>

</html>
