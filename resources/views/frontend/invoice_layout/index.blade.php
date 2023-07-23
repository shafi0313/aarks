@extends('frontend.layout.master')
@section('content')
<?php $p="il"; $mp="setting";?>
   <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <form action="{{route('invoice_layout.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 i_layout">
                                    @php
                                        $layout = $client->invoiceLayout->layout??'';
                                    @endphp
                                    <label class="radio-inline" style="font-size:15px;">
                                        <input type="radio" name="layout" {{$layout== 1 ? 'checked':''}} value="1" id="layout" required>  Service Layout

                                        <div style="padding-top:10px;">
                                            <img src="{{asset('frontend/assets/images/layouts/service.jpg')}}" class="img-responsive" alt="..."/>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="radio-inline" style="font-size:15px;">
                                        <input type="radio" class="layout" name="layout" {{$layout== 2 ? 'checked':''}} id="layout" value="2" required> Item Layout

                                        <div style="padding-top:10px;">
                                            <img src="{{asset('frontend/assets/images/layouts/item.jpg')}}" class="img-responsive" alt="..."/>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-4">
                                    <label class="radio-inline" style="font-size:15px;">
                                        <input type="radio" class="layout" name="layout" {{$layout== 3 ? 'checked':''}} id="layout" value="3" required> Professional Layout

                                    <div style="padding-top:10px;">
                                        <img src="" class="img-responsive" alt="..."/>
                                    </div>
                                </label>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-4">
                                    <label class="radio-inline" style="font-size:15px;">
                                        <input type="radio" class="layout" name="layout" {{$layout== 4 ? 'checked':''}} id="layout" value="4" required>Time Billing Layout
                                    </label>
                                    <div style="padding-top:10px;">
                                        <img src="https://www.aarks.com.au/upload/incoice_layout/timebilling.png" class="img-responsive"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="radio-inline" style="font-size:15px;">
                                        <input type="radio" class="layout" name="layout" {{$layout== 5 ? 'checked':''}} id="layout" value="5" required>Miscellaneous Layout
                                    </label>
                                    <div style="padding-top:10px;">
                                        <img src="https://www.aarks.com.au/upload/incoice_layout/miscell.png" class="img-responsive"/>
                                    </div>
                                </div>
                            </div> --}}
                            <br>
                            <div class="row justify-content-center">
                                <button type="submit" style="width: 300px;" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

    <!-- Footer Start -->

    <!-- Footer End -->

@stop
