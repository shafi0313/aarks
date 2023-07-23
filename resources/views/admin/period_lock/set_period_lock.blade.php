@extends('admin.layout.master')
@section('title','Period Lock')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Tools</li>
                <li class="active">Verify & Fixed Transactions</li>
                <li class="active">Period Lock</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
        </div>

        <div class="page-content">
            

            <div class="row">
                <div class="col-xs-12">

                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6" style="padding:20px; border:1px solid #999999;">
                                <form autocomplete="off" action="{{route('period_lock.store')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="client_id" id="client_id" value="{{$client->id}}" />
                                    <div class="form-group">
                                        <label style="color:red;">Lock Date</label>
                                        <input type="text" placeholder="dd/mm/yyyy"
                                            name="date" id="data_lock" class="form-control datepicker" value="{{$lock->date??''}}" />
                                    </div>

                                    <div class="form-group">
                                        <label style="color:red;">Password</label>
                                        <input type="password" name="password" id="new_password" autocomplete="off"
                                            class="form-control" required />
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
                                    {{-- <button type="button" class="btn btn-danger" id="sendEmail">Forgot Password?</button> --}}
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<!-- Script -->
<script>
    $('#sendEmail').on('click', function(){
    var client_id = $('#client_id').val();
    var urlmgs = "https://www.aarks.com.au/Peroid_lock/forgot_periodLock_pass";
        $.ajax({
        url:urlmgs,
        type:"POST",
        data:{client_id:client_id},
        success:function(data){
          alert('Password has been sent to your email address');
        }
      });
    });
</script>
    <script>
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            startDate:'0d',
        });
    </script>

@stop
