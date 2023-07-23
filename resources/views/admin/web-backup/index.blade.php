@extends('admin.layout.master')
@section('title','Web Backup')
@section('content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Database / Program File Full Backup</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div>
            <!-- /.nav-search -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="clearfix">
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header" style="text-align: center;">
                                <b>Database / Program File Full Backup</b>
                            </div>
                            <br>
                            <br>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="display:inline-block">
                                        <form action="{{route('backup.db')}}" method="POST">
                                            @csrf
                                            <input onclick="return waitt()" type="submit" class="form-control mt-2 btn btn-success"
                                                value="Backup Database">
                                        </form>
                                    </div>
                                    <div style="display:inline-block">
                                        <form action="{{route('backup.files')}}" method="POST">
                                            @csrf
                                            <input onclick="return waitt()" type="submit" class="form-control mt-2 btn btn-success"
                                                value="Backup Program File">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <h1 id="msg" class="text-center text-danger"></h1>
                            <h4 id="msg">Backup history</h4>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>File name</th>
                                            <th>File size</th>
                                            <th>Download type(BD/BPF)</th>
                                            <th>Delete backup history</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($backups) > 0)
                                        @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ preg_replace("/[^0-9 -]+/", "", $backup['filename']) }}</td>
                                            <td>{{niceSize($backup['size'])}}</td>
                                            <td>{{ preg_replace("/[^A-Z]+/", "", $backup['filename']) }}</td>
                                            <td>
                                                <div style="display: inline-block">
                                                    <form
                                                        action="{{route('backup.download',['name'=> $backup['filename'],'ext'=>$backup['extension']])}}"
                                                        method="post">
                                                        @csrf
                                                        <button class="btn btn-info btn-sm"><i
                                                                class="fa fa-download"></i></button>
                                                    </form>
                                                </div>
                                                <div style="display: inline-block">
                                                    <form
                                                        action="{{route('backup.delete',['name'=> $backup['filename'],'ext'=>$backup['extension']])}}"
                                                        method="post">
                                                        @csrf
                                                        <button  title="Backup Delete" class="btn btn-danger btn-sm"><i
                                                                class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <h5>No Record</h5>
                                            </td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->
@endsection
@push('custom_scripts')
<script>
    function waitt(){
        $("#msg").html('!!! Donot do any other action until download Complete.')
    }
</script>
@endpush
