@extends('admin.layout.master')
@section('title','Update User')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">Admin</a>
                    </li>
                    <li>
                        <a href="#">User</a>
                    </li>
                    <li class="active">Update User</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
                    </form>
                </div><!-- /.nav-search -->
            </div>

            <div class="page-content">



                

                <div class="page-header">
                    <h1>
                        Update User
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                        </small>
                    </h1>
                </div><!-- /.page-header -->
                <br>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <form class="form-horizontal" action="{{route('user.update',$user->id)}}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                               <div class="row">
                                   <label class="col-sm-3 control-label no-padding-right">Name </label>
                                   <div class="col-sm-9">
                                       <input type="text" name="name"  value="{{$user->name}}" placeholder="Enter User Name" class="col-xs-10 col-sm-8" />
                                   </div>
                               </div>
                                <div class="row">
                                    @if($errors->has('name'))
                                        <label class="col-sm-3 control-label no-padding-right"></label>
                                        <span class="text-danger col-sm-9"> {{$errors->first('name')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                               <div class="row">
                                   <label class="col-sm-3 control-label no-padding-right">Email </label>
                                   <div class="col-sm-9">
                                       <input type="text" name="email"  value="{{$user->email}}" placeholder="Enter User Email" class="col-xs-10 col-sm-8" />

                                   </div>
                               </div>
                                <div class="row">
                                    @if($errors->has('email'))
                                        <label class="col-sm-3 control-label no-padding-right"></label>
                                        <span class="text-danger col-sm-9"> {{$errors->first('email')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-sm-3 control-label no-padding-right">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password"  placeholder="Enter Password" class="col-xs-10 col-sm-8" />
                                    </div>
                                </div>
                               <div class="row">
                                   @if($errors->has('password'))
                                       <label class="col-sm-3 control-label no-padding-right"></label>
                                       <span class="text-danger col-sm-9"> {{$errors->first('password')}}</span>
                                   @endif
                               </div>
                            </div>

                            <div class="form-group">
                               <div class="row">
                                   <label class="col-sm-3 control-label"> Roles</label>
                                   @foreach($roles as $role)
                                       <label class=" control-label no-padding-right" style="margin-left: 10px;">
                                           <input class="ace ace-checkbox-2" type="radio" name="role" value="{{$role->id}}"
                                                  @foreach($user->roles()->get() as $userRole)
                                                  @if($userRole->id == $role->id)
                                                  checked
                                               @endif
                                               @endforeach
                                           >
                                           <span class="lbl"> {{ $role->name }} </span>
                                       </label>
                                   @endforeach
                               </div>

                              <div class="row">

                                  @if($errors->has('role'))
                                      <label class="col-sm-3 control-label"></label>
                                      <span class="text-danger" style="margin-left: 1%;"> {{$errors->first('role')}}</span>
                                  @endif
                              </div>
                            </div>

                            <div class="space-4"></div>



                            <div class="clearfix form-actions ">
                                <div class="text-center">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Submit
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <a href="{{route('user.index')}}">
                                        <button class="btn btn-danger" type="button">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div><!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->

    </div>
@stop
