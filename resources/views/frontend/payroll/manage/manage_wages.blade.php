@extends('frontend.layout.master')
@section('title','Manage Wages')
@section('content')
<?php $p="mw"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading d-flex">
                                <p>Wages Name</p>
                                <button type="button" class="btn m_c_b ml-auto" data-toggle="modal" data-target="#exampleModal">
                                    Add Wages Category
                                </button>
                            </div>

                            <table id="example" class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Wages Name</th>
                                        <th>Type of Wages</th>
                                        <th>Wages Type</th>
                                        <th>Regular Rate</th>
                                        <th>Hourly Rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stanwages as $stan)
                                    <tr>
                                        <td>{{$stan->name}}</td>
                                        <td class="text-center">{{$stan->link_group}}</td>
                                        <td>{{$stan->type}}</td>
                                        <td class="text-right">{{number_format($stan->regular_rate,2)}}</td>
                                        <td class="text-right">{{number_format($stan->hourly_rate,2)}}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    @foreach ($wages as $wage)
                                    <tr>
                                        <td>{{$wage->name}}</td>
                                        <td class="text-center">{{$wage->link_group}}</td>
                                        <td>{{$wage->type}}</td>
                                        <td class="text-right">{{number_format($wage->regular_rate,2)}}</td>
                                        <td class="text-right">{{number_format($wage->hourly_rate,2)}}</td>
                                        @if ($wage->action == 1)
                                        <td style="text-align: center">
                                            <a href="{{route('wages.edit',$wage->id)}} " class="pemcil">
                                                <i class="fas fa-pencil-alt text-info"></i>
                                            </a> &nbsp;&nbsp;&nbsp;
                                            <a href="{{route('wages.delete',$wage->id)}} " class="trash" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

 <!-- Modal -->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" >
      <div class="modal-content">
        <div class="modal-header card-heading text-light">
          <h5 class="modal-title  " id="exampleModalLabel">Add New Wages</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="clientWages" action="{{route('wages.store')}} " method="post" autocomplete="off">
                @csrf
            <div class="form-group">
                <label class="t_b">Name: </label><strong class="t_red">*</strong>
                <input class="form-control form-control-sm" name="name" type="text">
                <input type="hidden" name="client_id" value="{{client()->id}} ">
                <input type="hidden" name="action" value="1">
            </div>
            <div class="form-group">
                <label for="" class="t_b">Type: </label><strong class="t_red">*</strong>
                <select class="form-control form-control-sm" name="type" >
                    <option value="Salary">Salary</option>
                    <option value="Hourly">Hourly</option>
                </select>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="link_group" class="t_b">Link Income Group<strong style="color:red;">*</strong></label>
                    <select class="form-control" name="link_group" id="link_group">
                        <option value="1" selected>Salary/Wages</option>
                        <option value="2">Overtime</option>
                        <option value="3">Bonus and Commission</option>
                        <option value="4">Allowance</option>
                        <option value="5">Director’s Fees </option>
                        <option value="6">Lump Sum Tuple</option>
                        <option value="7">ETP Tuple </option>
                        <option value="8">CDEP</option>
                        <option value="9">Salary Secriface</option>
                        <option value="10">Exempt Foreign Income</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                            <input type="radio" name="regularrate" id="regularrate" value="regular">
                            Regular Rate Multiplied by:
                        </label>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="number"  name="regular_rate" id="regularrateval">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                            <input type="radio" name="regularrate" id="regularrate" value="fixed">
                            Fixed Hourly Rate of:
                        </label>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="number" name="hourly_rate" id="fixedhourlyval">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="checkbox col-md-12">
                    <label>
                        <input type="checkbox" name="automatically" id="automatically" value="1"> Automatically Adjust Base Hourly or Base Salary Details
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-success float-right btn3">Save</button>
        </form>

        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
    </div>
  </div>
    <!-- Footer -->

    <!-- /Footer -->
    <!-- inline scripts related to this page -->
        <script>
            $('#clientWages input').on('change', (e)=> {
                var radiovalue = $('input[name=regularrate]:checked', '#clientWages').val();
                if(radiovalue == 'regular'){
                $('#regularrateval').removeAttr('disabled', 'disabled');
                $('#fixedhourlyval').attr('disabled', 'disabled');
                } else{
                $('#regularrateval').attr('disabled', 'disabled');
                $('#fixedhourlyval').removeAttr('disabled', 'disabled');
                }
            });
            $(document).ready(function() {
                    $('#example').DataTable( {
                        "order": [[ 0, "asc" ]]
                    } );
                } );
        </script>
@stop
