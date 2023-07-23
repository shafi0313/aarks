@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="socb"; $mp="purchase"?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">New Invoice for Business Activity: </strong>
                                </div>
                                {{-- <div class="col-2 form-check">
                                    <input class="form-check-input" type="radio" name="" value="">
                                    <label class="form-check-label">
                                        Default radio
                                    </label>
                                </div>
                                <div class="col-2 form-check">
                                    <input class="form-check-input" type="radio" name="" value="">
                                    <label class="form-check-label">
                                        Default radio
                                    </label>
                                </div>
                                <div class="col-2 form-check">
                                    <input class="form-check-input" type="radio" name="" value="">
                                    <label class="form-check-label">
                                        Default radio
                                    </label>
                                </div> --}}
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-2 form-group">
                                    <label class="t_b">Customer Name:</label>
                                    <select class="form-control" name="">
                                        <option disabled selected value>Select Customer</option>
                                        <option value="new">New Customer</option>
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label class="t_b">Invoice Date: </label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-2 form-group">
                                    <label class="t_b">Expiry: </label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-2 form-group">
                                    <label class="t_b">Invoice No: </label>
                                    <input class="form-control" disabled type="text" name="" placeholder="000001">
                                </div>
                                {{-- <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control" type="text" name="" placeholder="Your Reference">
                                </div> --}}
                                <div class="col-2 form-group">
                                    <label class="t_b">Our Reference: </label><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ourReference">...</button>
                                    <input class="form-control" type="text" name="" placeholder="Our Reference">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-11 form-group">
                                    <label class="t_b">Quote terms and Conditions: </label>
                                    <textarea class="form-control" rows="2" placeholder="Quote terms and Conditions"></textarea>
                                </div>
                                <div class="col-sm-1">
                                    <br><br>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">Add</button>
                                </div>
                            </div>

                            <div class="row mx-auto">
                                <div class="form-group">
                                    <label  class="t_b">Job Title: </label>
                                    <input class="form-control form-control-sm" type="text" name="" placeholder="Job Title">
                                </div>

                                <div class="form-group">
                                    <label class="t_b">Job Description:</label><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#jobDescription">...</button>
                                    <textarea class="form-control form-control-sm" rows="1" name=""placeholder="Job Description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="t_b">Price: </label>
                                    <input class="form-control form-control-sm" type="Number" name="" placeholder="Price">
                                </div>
                                <div class="form-group">
                                    <label class="t_b">Disc %: </label>
                                    <input class="form-control form-control-sm" type="Number" name="" placeholder="Disc %">
                                </div>
                                <div class="form-group">
                                    <label class="t_b">Freight %: </label>
                                    <input class="form-control form-control-sm" type="Number" name="" placeholder="Freight %">
                                </div>
                                <div class="form-group">
                                    <label class="t_b">Select Account: </label>
                                    <select class="form-control form-control-sm" name="">
                                        <option disabled selected value>Select Account</option>}
                                        <option value=""></option>}
                                        option
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="t_b">Tax: </label>
                                    <select class="form-control form-control-sm" name="">
                                        <option value="">Yes</option>
                                        <option value="">No</option>
                                        option
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <button class="btn btn-success btn-sm btn3" type="submit">Add</button>
                            </div>

                            <hr>

                            <div class="card-heading">
                                <h3>Data List</h3>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 15px;">
                                    <tr>
                                        <th width="4%">SN</th>
                                        <th width="%">Description</th>
                                        <th width="8%">Price </th>
                                        <th width="7%">Disc %</th>
                                        <th width="11%">Freight Chrg</th>
                                        <th width="10%">Account</th>
                                        <th width="9%">Tax Rate</th>
                                        <th width="12%">Amount AUD</th>
                                        <th width="3%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $x=1 @endphp
                                    <tr>
                                        <td class="text-center">{{ $x++ }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="action">
                                                <a href="" class="trash">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">Total </th>
                                        <th class="text-center sub-total"></th>
                                        <th><input type="hidden" name="total_amount" id="total_amount" value=""></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <br>
                            <div class="" align="right">
                                <input type="submit" class="btn btn-info" value="Preview & Save">
                                <input type="submit" class="btn btn-success" value="Print & Save">
                                <input type="submit" class="btn btn-secondary" value="E-mail & Save" >
                                <input type="submit" class="btn btn-primary" value="Print E-mail & Save">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->


  <!-- Our Reference Modal Start-->
  <div class="modal fade" id="ourReference" tabindex="-1" role="dialog" aria-labelledby="ourReferenceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="" id="ourReferenceLabel">Our Reference</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <select class="form-control" id="reference">
                  <option>1</option>
                </select>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Our Reference Modal End-->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Save a New Template</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Use Selected Template</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-group">
                            <label>Template Title: </label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <div class="form-group">
                            <label>Template Details: </label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <strong style="color:red">please click once it will be appaer your condition space in the quote</strong>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Job Description Modal Start -->
<div class="modal fade" id="jobDescription" tabindex="-1" role="dialog" aria-labelledby="jobDescriptionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#jobHome" role="tab" aria-controls="home" aria-selected="true">Save a New Job Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#jobProfile" role="tab" aria-controls="profile" aria-selected="false">Use Selected Job Description</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="jobHome" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-group">
                            <label>Job Title: </label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <div class="form-group">
                            <label>Job Description: </label>
                            <textarea class="form-control" name="" id="" cols="30" rows="5"></textarea>
                            {{-- <input class="form-control" type="text" name="" value=""> --}}
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Income Account</label>
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option value="">Income Account</option>
                                <option value="">Gross Received from customers</option>
                                <option value="">Commission Received</option>
                                <option value="">Gross Received non GST</option>
                            </select>
                          </div>
                    </div>
                    <div class="tab-pane fade" id="jobProfile" role="tabpanel" aria-labelledby="profile-tab">
                        <strong style="color:red">please click once it will be appaer your Job Description space in the quote</strong>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Template</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

    <!-- Footer Start -->

    <!-- Footer End -->


    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );

    </script>

@stop
