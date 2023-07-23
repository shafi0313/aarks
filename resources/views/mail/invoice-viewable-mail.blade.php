<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Title Goes Here</title>
        @include('frontend.bootstrap-3')
        <style>
            hr {
                border-top: 2px solid #585858;
            }

            .hr {
                margin-top: 20px;
                margin-bottom: 20px;
                border: 0;
                border-top: 5px solid #585858;
            }
        </style>
    </head>

    <body style="background: #f3f4f5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="row" style="background: #fff;">
                        <div class="col-lg-12 text-center">
                            <h1 class="text-uppercase text-info text-center text-bold"><b>AARKS</b></h1>
                        </div>
                        <div class="col-lg-12">
                            <p>To</p>
                            <p><b>{{$customer->name}}</b></p>
                            <p>Your invoice ready to view. <a
                                    href="{{route('inv.email_view_report',['service',$invoice->inv_no, $client->id])}}"
                                    class="btn btn-primary pull-right">View Invoice</a> </p>
                            <br>
                            <hr>
                            <p>Please contact us immediately if you are unable to detach or download your Invoice.</p>
                            <div class="hr"></div>
                            <p>Thanks,</p>
                            <p>{{$client->fullname}}</p>
                        </div>
                        <div class="col-lg-12 text-center">
                            <p>&copy; {{now()->format('Y')}} All right reserved.</p>
                            <p>Powered By: <b><a href="https://aarks.net.au" target="_blank">AARKS</a></b> Sponsored By:
                                <b>Focus Taxation and Accounting Pty Ltd.</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js">
        </script>
    </body>

</html>
