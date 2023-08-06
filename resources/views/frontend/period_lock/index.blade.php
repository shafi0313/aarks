@extends('frontend.layout.master')
@section('title', '')
@section('content')
    <?php $p = 'pl';
    $mp = 'setting'; ?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('front_period_lock.store') }}" method="post">
                                @csrf @method('POST')
                                <label class="required">Date Lock </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" name="date" class="form-control datepicker" value="{{$lock->date??''}}" required>
                                </div>

                                <br>
                                <label class="required">Password </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                    </div>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <br>
                                <button type="submit" class="btn btn-success btn-block">Update/Save</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <script>
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            startDate:'0d',
        });
    </script>
@stop
