@extends('frontend.layout.master')
@section('title','Manage Wages')
@section('content')
<?php $p="md"; $mp="payroll";?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">

                    <div class="card-body">
                        <div class="card-heading d-flex">
                            <p>Update Employment Classification Name</p>
                        </div>

                        <form action="{{ route('classification.update',$classification->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label class="t_b">Employment Classification Name: </label><strong
                                    class="t_red">*</strong>
                                <input class="form-control form-control-sm" type="text" name="name"
                                value="{{$classification->name}}">
                            </div>
                            <div class="form-group">
                                <label for="" class="t_b">Classification Status: </label><strong
                                    class="t_red">*</strong>
                                <input class="form-control form-control-sm" type="text" name="status"  value="{{$classification->status}}">
                            </div>

                            <button type="submit" class="btn btn-success float-right btn3">Update</button>
                            <br>
                            <br>
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
