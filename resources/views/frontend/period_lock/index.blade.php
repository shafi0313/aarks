@extends('frontend.layout.master')
@section('title','')
@section('content')
<?php $p="pl"; $mp="setting";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <form>
                                <label>Date Lock</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="date" class="form-control">
                                </div>

                                <label>Old password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control">
                                </div>

                                <label>New password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" class="form-control">
                                </div>


                                <br>
                                <button type="button" class="btn btn-success btn-block">Update/Save</button>
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

@stop
