<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Trial Balance</title>
        @include('frontend.print-css')
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="center">
                        <h1 style="padding: 0;margin:0"><b>{{$client->fullname}}</b></h1>
                        <h3 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h3>
                        <h4><b>Trial Balance as at: {{\Carbon\Carbon::parse($date)->format('d/m/Y')}}</b></h4>
                    </div>
                    <style>
                        .text-danger {
                            color: red;
                        }
                    </style>
                </div>
                <div class="col-lg-12">
                    @include('admin.reports.trial_balance.table')
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </body>

</html>
