<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Comparative Balance Sheet - {{ now() }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
            integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        @include('frontend.print-css')
        <style>
            .reportH {
                /* margin: 50px 0; */
            }

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 0 8px;
                /* border: none !important; */
            }

            .table {
                /* border: none !important; */
                /* width: 100%; */
            }

            .t-right {
                text-align: right !important;
            }

            .dep-tbl thead tr th,
            .dep-tbl thead tr td {
                font-size: 12px !important;
            }

            .dep_title {
                margin-left: 300px;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6" align="center">
                    <h2 style="padding: 0;margin:0"><b>{{ $client->fullname}}</b></h2>
                    <h2 style="padding: 0;margin:0"><b>ABN {{$client->abn_number}}</b></h2>
                    <br>
                    <strong style="font-size:16px;"><u>Detailed Balance Sheet as at:
                            {{$date->format('d/m/Y')}}</u></strong>
                    <br />
                </div>
                <div class="col-md-12" style="padding-top:10px; ">
                    <div class="panel-body">
                        @include('admin.reports.comperative_balance_sheet.table')
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
