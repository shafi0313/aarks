<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <title>INVOICE</title>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Dear, <strong>{{$customer->name}}</strong></p>
                    <p> Please find attached invoice for your attention to take necessary action as your earliest convenient time.</p>
                    <p>We really appreciate your contributions to our organization and look forward to work with you.</p> <br><br>

                    <p><b>If you have any queries please do not hesitate to contact us</b></p> <br><br>


                    <p>Thanks for downloading Invoice.</p> <br>
                    <p>Best Regards</p>
                    <p><b>{{$client->fullname}}</b></p>
                    <p>{{$client->address}}</p>
                    <p>!!!!Stay with AARKS. Keep distance in public place!!!</p>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
    </body>

</html>
