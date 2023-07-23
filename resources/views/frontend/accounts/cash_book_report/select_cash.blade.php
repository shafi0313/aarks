@extends('frontend.layout.master')
@section('title', 'Select Activity')
@section('content')
    <?php $p = 'cbr';
    $mp = 'acccounts'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-heading">
                            <h3>Cash Book Report</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('cashbook.report', [$client->id, $profession->id]) }}" method="get"
                                autocomplete="off">
                                <div class="row justify-content-center pt-3">
                                    <div class="col form-group">
                                        <select class="form-control" name='office_id'>
                                            <option disabled selected value>Select Cash Centre/Office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                            id="start_date" name="start_date" placeholder="Form date" required="">

                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control datepicker" data-date-format="dd/mm/yyyy"
                                            id="end_date" name="end_date" placeholder="To date" required="">

                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary"
                                            style="background:#ff5733 !important; color:white !important; width: 150px">
                                            Report
                                        </button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Page Content End -->
    <div class="modal fade entrycenter" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" id="entrydata" action="{{ route('cashbook.newoffice') }}" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel" style="color:green !important;">New Cash Centrre/office
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                        <div class="form-group">
                            <label for="exampleInputEmail1" style="color:red;">Office Name</label>
                            <input type="text" class="form-control" name="name" required="" id="name"
                                placeholder="Office Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1" style="color:red;">Office Address:</label>
                            <textarea class="form-control" rows="4" name="address" required="" id="address"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- Footer Start -->

    <!-- Footer End -->
    <script>
        $('input').on("keypress", function(e) {
            /* ENTER PRESSED*/
            if (e.keyCode == 13) {
                /* FOCUS ELEMENT */
                var inputs = $(this).parents("form").eq(0).find(":input");
                var idx = inputs.index(this);
                if (idx == inputs.length - 1) {
                    inputs[0].select()
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
        });


        // $("#entrydata").submit(function(e){
        // 	e.preventDefault();
        //     var postData = $(this).serializeArray();
        //     var formURL = $(this).attr("action");
        //     $.ajax({
        //         url        : formURL,
        //         timeout    : 1000,
        //         type       : "POST",
        //         async      : false,
        //         crossDomain: true,
        //         data       : postData,
        //         success    : function(data){
        //             $('#name').val('');
        //             $('#address').val('');
        //             $('#enterid').html(data);
        //             location.reload();
        //         }
        //     });
        // });

        $('.jourandelete').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var urlj = "https://aarks.net.au/books/Journal_entry/jourandelete";
            $.ajax({
                url: urlj,
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    $("#jouranlist").html(data);

                }
            });
        });
    </script>
@stop
