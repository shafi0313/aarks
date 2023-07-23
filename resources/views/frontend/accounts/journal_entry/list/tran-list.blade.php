@extends('frontend.layout.master')
@section('title','Select Activity')
@section('content')
<?php $p="cjl"; $mp="acccounts"?>
<!-- Page Content Start -->
<section class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-12">
                            <div class="col-xs-12">
                                <h2>Journal List</h2>
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>SL </th>
                                            <th>Journal Serial </th>
                                            <th>Journal Date</th>
                                            <th>Journal Ref</th>
                                            <th>Trn.ID</th>
                                            <th>Action</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($journals->groupBy('journal_number') as $journal)
                                        <?php $journal = $journal->first(); ?>
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>
                                                <a href="{{route('client.jl_trans_show',[$client->id,$profession->id,$journal->tran_id])}}">
                                                    JNL-{{$journal->journal_number}}
                                                </a>

                                            </td>
                                            <td>{{$journal->date->format('d/m/Y')}}</td>
                                            <td>{{$journal->narration}}</td>
                                            <td>{{$journal->tran_id}}</td>
                                            <td>
                                                <a title="Journal List Edit" href="{{route('client.jl_edit', $journal->id)}}" class="fa fa-edit btn btn-info btn-sm"></a>
                                                <a title="Journal List Delete"  href="{{route('client.jl_delete', $journal->id)}}" onclick="return confirm('You will lost all data!')" class="fa fa-trash btn btn-danger btn-sm"></a>
                                            </td>
                                        </tr>
                                        @endforeach
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
</section>
<!-- Page Content End -->

<!-- Footer Start -->

<!-- Footer End -->

@stop
