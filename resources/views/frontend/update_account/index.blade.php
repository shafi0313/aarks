@extends('frontend.layout.master')
@section('content')
<?php $p="upr"; $mp="sales";?>

<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-danger">{{$error}}</li>
                            @endforeach
                        </ul>
                        @endif
                        <style>
                            .package a[title] {
                                color: red
                            }
                        </style>
                        <div align="center" class="package">
                            <span style="color:red; font-weight:400; font-size:14px;">Please read before Payment
                                <i class="fas fa-hand-point-right"></i>
                                <a class="btn btn-secondary" data-toggle="tooltip" data-html="true"
                                    data-placement="bottom"
                                    title="Please pay your plan subscription fee to the BSB 302-162,Account Number 1963091. Account Name : AARKS AUSTRALIA PTY LTD. Please note that payment bank receipt must be attached with the note, Without receipt the purchase request won't be valid."
                                    href="" style="font-size:15px;">Precaution!</a>
                            </span>
                        </div>
                        <hr>

                        <form action="{{route('upgradeRequest')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <input type="hidden" name="client_id" value="{{client()->id}}">
                            <div class="form-group">
                                <label>Select Package:</label>
                                <select required class="form-control" name="subscription_id" id="pack_name">
                                    <option disabled selected value>Select Package</option>
                                    @forelse ($plans as $plan)
                                    <option value="{{$plan->id}}" data-amount="{{$plan->amount}}">{{$plan->name}}</option>
                                    @empty
                                    <option value="">Subscription Table Empty</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Duration:</label>
                                <select required class="form-control" name="duration" id="duration">
                                    <option value="1" selected>Month</option>
                                    <option value="2">2 Month</option>
                                    <option value="3">3 Month</option>
                                    <option value="6">6 Month</option>
                                    <option value="12">Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Amount:</label>
                                <input required type="number" class="form-control" name="amount" id="amount"  readonly>
                            </div>

                            <div class="row justify-content-center">
                                <div class="form-group file_upload">
                                    <label for="exampleInputEmail1">Payment Receipt</label>
                                    <div class="upload" data-target="#rcpt" data-type="rcpt">
                                        <i class="fas fa-cloud-upload-alt" id="upicon"></i>
                                        <img src='' alt=".." style="display:none" id='blah' class='img-fluid'/>
                                    </div>
                                    <input required type="file" name="rcpt" id="my_file" style="display: none;" accept="image/*"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Note:</label>
                                <textarea required class="form-control" rows="3" placeholder="Message Here..." name="message"></textarea>
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
// End image
    $(".upload").click(function() {
        $("input[id='my_file']").click();
    });
    $("#pack_name").on('change',function(){
        const amount = $("#pack_name option:selected").data('amount');
        const duration = $('#duration').val();
        $('#amount').val(amount * duration);
    });
    $("#duration").on('change',function(){
        const amount = $("#pack_name option:selected").data('amount');
        $('#amount').val(amount * $(this).val());
    });
</script>

@stop
