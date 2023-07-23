@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="cb"; $mp="acccounts";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <label class="col-3" style="font-size: 20px; color: green; font-weight: bold;padding-top: 15px">Select Cash Centre/Office: </label>
                                <div class="col-5 form-group" style="padding:10px;">
                                    <select class="form-control mx-3" onchange="location = this.value;">
                                        <option disabled selected value>Select Cash Centre/Office</option>
                                        @foreach ($offices as $office)
                                        <option value="{{route('cashbook.dataentry',[$office->client_id,$office->profession_id,$office->id])}}">{{$office->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3" align="left" style="padding-top:10px;">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-backdrop="static"
                                        data-keyboard="false" style="background:#ff5733 !important; color:white !important;">Add Centre/office</button>
                                </div>
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
            <form method="post" id="entrydata" action="{{route('cashbook.newoffice')}}" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel" style="color:green !important;">New Cash Centrre/office</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input type="hidden" name="profession_id" value="{{$profession->id}}">
                    <div class="form-group">
                        <label for="exampleInputEmail1" style="color:red;">Office Name</label>
                        <input type="text" class="form-control" name="name" required="" id="name"
                            placeholder="Office Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" style="color:red;">Office Address:</label>
                        <textarea class="form-control" rows="4" name="address" required=""
                            id="address"></textarea>
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

$('.jourandelete').on('click', function(e){
	e.preventDefault();
	var id = $(this).attr('data-id');
	var urlj = "https://aarks.net.au/books/Journal_entry/jourandelete";
	$.ajax({
		url:urlj,
		type:"POST",
		data:{id:id},
		success:function(data){
		$("#jouranlist").html(data);

		}
	});
});

</script>
@stop
