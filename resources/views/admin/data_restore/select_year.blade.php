@extends('admin.layout.master')
@section('title','Period List')
@section('content')

      <div class="main-content">
        <div class="main-content-inner">
          <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
              <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{ route('admin.dashboard') }}">Home</a>
              </li>
              <li>Close Year</li>
              <li class="active">Financial Close Year &amp; Data backup</li>
              <li class="active">Select Activity</li>
              <li class="active">Select Financial Year</li>
            </ul><!-- /.breadcrumb -->

            {{-- <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
            </div><!-- /.nav-search --> --}}
          </div>

          <div class="page-content">            
            

            <div class="row">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->

                <div class="row">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                    <form method="post" enctype="multipart/form-data" action=">
                      <input type="hidden" name="profession_id" value="27" />
                      <input type="hidden" name="client_id" value="630" />
                      <div class="form-group">
                        <label for="exampleInputPassword1">Select Financial Year</label>
                        <select class="form-control" name="finalshial_year" required>
                          <option disabled selected value>Select Financial Year</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputFile">Import Backup File</label>
                        <input type="file" id="csvfile" name="csvfile">
                      </div>
                      <button type="submit" class="btn btn-success">Import</button>
                    </form>
                  </div>
                </div>   

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

@endsection
