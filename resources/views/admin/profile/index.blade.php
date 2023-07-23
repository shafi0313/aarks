@extends('admin.layout.master')
@section('title', $user->name.' - Profile')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Dashboard</li>
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
            <div class="page-header">
                <h1>
                    {{$user->name}} Profile Page
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <div id="user-profile-2" class="user-profile">
                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-18">
                                <li class="active">
                                    <a data-toggle="tab" href="#home">
                                        <i class="green ace-icon fa fa-user bigger-120"></i>
                                        Profile
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#feed">
                                        <i class="orange ace-icon fa fa-rss bigger-120"></i>
                                        Activity Feed
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#friends">
                                        <i class="blue ace-icon fa fa-users bigger-120"></i>
                                        Friends
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#pictures">
                                        <i class="pink ace-icon fa fa-cogs bigger-120"></i>
                                        Settings
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content no-border padding-24">
                                <div id="home" class="tab-pane in active">
                                    @include('admin.profile.tabs.home')
                                </div><!-- /#home -->

                                <div id="feed" class="tab-pane">
                                    @include('admin.profile.tabs.feed')
                                </div>

                                <div id="friends" class="tab-pane">
                                    @include('admin.profile.tabs.friends')
                                </div><!-- /#friends -->

                                <div id="pictures" class="tab-pane">
                                    @include('admin.profile.tabs.setting')

                                </div><!-- /#pictures -->
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </div>
</div>
@endsection
