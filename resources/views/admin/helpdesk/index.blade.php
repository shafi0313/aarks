@extends('admin.layout.master')
@section('title', 'Help Desk')
@section('style')
<link rel="stylesheet" href="{{ asset('admin/assets/summernote/summernote.min.css') }}">
@endsection
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li>Help Desk</li>
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
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="card">
                        <div class="card-header">
                            <h2>Help Desk Manage
                                <!-- Button trigger modal -->
                                <div class="pull-right">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#helpCat">
                                        Add Category / Menu
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#help">
                                        Add Help
                                    </button>
                                </div>
                            </h2>
                            <!-- Modal -->
                            <div class="modal fade" id="helpCat" tabindex="-1" role="dialog"
                                aria-labelledby="helpCatLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title" id="helpCatLabel">Add Category/Page</h2>
                                        </div>
                                        <form action="{{ route('helpdesk.category') }}" method="post" autocomplete="off"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="1">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Menu Name</label>
                                                    <input type="text" required class="form-control" id="name"
                                                        name="name" value="{{old('name')}}" placeholder="Ex: Sales">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="helpLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title" id="helpLabel">Help Topic Add</h2>
                                        </div>
                                        <form action="{{ route('helpdesk.store') }}" method="post" autocomplete="off"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="1">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="parent_id">Category / Menu</label>
                                                    <select name="parent_id" required class="form-control"
                                                        id="parent_id">
                                                        <option value="">Select A Page</option>
                                                        @foreach($desks->where('parent_id', 0) as $dsk)
                                                        <option value="{{ $dsk->id }}">{{ $dsk->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Menu Name</label>
                                                    <input type="text" required class="form-control" id="name"
                                                        name="name" value="{{old('name')}}" placeholder="Ex: Sales">
                                                </div>
                                                <div class="form-group">
                                                    <label>Enter Topics</label>
                                                    <input type="text" class="form-control" id="title" name="title"
                                                        value="{{old('title')}}" placeholder="Ex: How to access sales">
                                                </div>
                                                <div class="form-group">
                                                    <label>Details</label>
                                                    <textarea id="summernote" placeholder="Enter details"
                                                        class="form-control" id="description" name="description"
                                                        placeholder="Enter details">{{old('description')}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="thumbnail">Thumbnail</label>
                                                    <input type="file" name="thumbnail" id="thumbnail"
                                                        class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="margin: 30px 0;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">ID</th>
                                        <th>Category</th>
                                        <th>Title</th>
                                        <th style="width: 60px">View</th>
                                        <th style="width: 100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($desks as $i => $desk)
                                    <tr class="bg-primary desk-{{$desk->id}}">
                                        <td>{{$i+1}}</td>
                                        <td colspan="2">{{$desk->name}}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Toggle Sub Desk" class="btn btn-warning btn-sm toggleSub mb-2 toggleSub{{$desk->id}}" data-is_show="0" data-id="{{$desk->id}}"><i class="fa fa-plus"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <span data-route="{{route('helpdesk.edit', $desk->id)}}"
                                                class="btn btn-info btn-sm" onclick="editHelp($(this).data('route'))">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            <form style="display: inline-block;"
                                                action="{{route('helpdesk.destroy', $desk->id)}}" method="post">
                                                @csrf @method('delete')
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="editModalContent"></div>
</div>
@stop
@section('script')
<script src="{{ asset('admin/assets/summernote/summernote.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
        $('body').on('click', '.toggleSub', function() {
            var case_id = $(this).data('id');
            var is_show = $(this).data('is_show');
            $(this).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
            if(is_show == 0){
                $(this).data('is_show',1);
                getCaseSubCats(case_id);
            } else {
                $(this).html('<i class="fa fa-plus"></i>');
                $(this).data('is_show',0);
                $(".sub-desk-"+case_id).remove();
            }
        });
    });
    function editHelp(url) {
        $.get(url,res=>{
            $("#editModalContent").html(res);
            $("#editModal").modal('show');
        });
    }

    var getCaseSubCats = function(case_id){
        $.ajax({
            url : "{{route('helpdesk.subcategory')}}",
            type: "get",
            // dataType: "json",
            data: {
                id:case_id
            },
            success: function(res)
            {
                $(".toggleSub"+case_id).html('<i class="fa fa-minus"></i>');
                $(".desk-"+case_id).after(res.tr);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".ajax_loader").hide();
                alert("error");
            }
        });
    }
</script>
@endsection
