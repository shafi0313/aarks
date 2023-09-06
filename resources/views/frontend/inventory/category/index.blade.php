@extends('frontend.layout.master')
@section('title', 'Category')
@section('content')
    <?php $p = 'invCat';
    $mp = 'inventory'; ?>
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card">
                        {{-- <div class="card-header bg-info">
                        <h3 class="text-light">Inventory Category
                        <button type="button" class="btn btn-secondary pull-right" data-toggle="modal" data-target="#add-category">
                            Add Category
                        </button>
                        </h3>
                    </div> --}}
                        <div class="card-body">
                            <div class="card-heading d-flex">
                                <p>Inventory Category</p>
                                <button type="button" class="btn btn-secondary ml-auto" data-toggle="modal"
                                    data-target="#add-category">
                                    Add Category
                                </button>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <table id="DataTable" class="table table-bordered table-hover table-sm display">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th style="width: 15%" class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($invs as $inv)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td style="color: green">{{ $inv->name }}</td>
                                            <td align="center">
                                                <i title="Invoice Category Add"
                                                    class="btn btn-sm btn-success fa fa-plus addSub" data-toggle="modal"
                                                    data-target="#add-sub" data-id="{{ $inv->id }}"></i>
                                                <i title="Invoice Category Edit" class="btn btn-sm btn-info fa fa-edit edit"
                                                    data-toggle="modal" data-target="#edit"
                                                    data-url="{{ route('inv_category.update', $inv->id) }}"
                                                    data-name="{{ $inv->name }}"></i>
                                                {{-- <a href="{{route('inv_category.show', $inv->id)}}" onclick="return confirm('are you sure?')" class="btn btn-danger fa fa-trash-alt"></a> --}}
                                            </td>
                                        </tr>
                                        @if ($inv->subcat->count() > 0)
                                            @foreach ($inv->subcat as $sinv)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td style="color: rgb(245, 111, 134)">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $sinv->name }}
                                                    </td>
                                                    <td align="center">
                                                        <i class="btn btn-sm btn-info fa fa-edit edit" data-toggle="modal"
                                                            data-target="#edit"
                                                            data-url="{{ route('inv_category.update', $sinv->id) }}"
                                                            data-name="{{ $sinv->name }}"></i>
                                                        <a href="{{ route('inv_category.show', $sinv->id) }}"
                                                            onclick="return confirm('are you sure?')"
                                                            class="btn btn-sm btn-danger fa fa-trash-alt"></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Add Cat Modal -->
        <div class="modal fade" id="add-category" tabindex="-1" role="dialog" aria-labelledby="add-categoryLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('inv_category.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add-categoryLabel">Add New Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Category Name <strong style="color:red;">*</strong></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Category Name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Sub Cat Modal -->
        <div class="modal fade" id="add-sub" tabindex="-1" role="dialog" aria-labelledby="add-subLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('inv_category.addSub') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add-subLabel" style="color:red;">Add New Sub Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="categoryName" name="parent_id">
                            <div class="form-group">
                                <label for="sub-name">Sub Category Name <strong style="color:red;">*</strong></label>
                                <input type="text" class="form-control" id="sub-name" name="name"
                                    placeholder="Sub Category Name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--Edit Modal -->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="" method="POST" autocomplete="off" id="editForm">
                    @csrf @method('put')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editLabel" style="color:red;">Update Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="categoryName" name="parent_id">
                            <div class="form-group">
                                <label for="edit-name">Name <strong style="color:red;">*</strong></label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('#DataTable').DataTable();
        });
        $(".addSub").on('click', function() {
            $('#categoryName').val($(this).data('id'));
        });
        $(".edit").on('click', function() {
            $('#editForm').attr('action', $(this).data('url'));
            $('#edit-name').val($(this).data('name'));
        });
    </script>
@stop
