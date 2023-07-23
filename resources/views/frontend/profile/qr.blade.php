@extends('frontend.layout.master')
@section('title', client()->company)
@section('content')
<?php $p="ul"; $mp="setting";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('profile.logoStore')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="client_id" value="{{client()->id}}">
                                <div class="form-group file_upload_">
                                    <label style="font-size: 25px;">Upload Logo</label>
                                    <div class="upload_" data-target="#logo" data-type="logo">
                                        <p>Browse</p>
                                        <i class="fas fa-cloud-upload-alt" id="upicon"></i>
                                        <img src='' alt=".." style="display:none" id='blah' class='img-fluid' />
                                    </div>
                                    <input type="file" name="logo" id="my_file" style="display: none;" required />
                                </div>
                                <button class="btn btn-success btn-block" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->

   <script>
       // read image before submit
    function readURL(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
    $('#blah').attr('src', e.target.result);
    $('#blah').show();
    $('#upicon').hide();
    }
    reader.readAsDataURL(input.files[0]);
    }
    }
    $("#my_file").change(function() {
    readURL(this);
    });
        $(".upload_").click(function() {
            $("input[id='my_file']").click();
        });
    </script>

@stop


