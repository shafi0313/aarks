@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="so"; $mp="purchase";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="color:green; font-size:20px;">New Service Order for Business Activity: </strong>
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
                                </div>
                                <div class="col-2 form-check">
                                    <input class="form-check-input" type="radio" name="" value="">
                                    <label class="form-check-label">
                                        Default radio
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-2 form-group">
                                    <label style="color:red; font-weight: bold;">Supplier Name</label>
                                    <select class="form-control" name="">
                                        <option disabled selected value>Select Supplier</option>
                                        <option value="new">New Supplier: </option>
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Order Date: </label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Expiry: </label>
                                    <input class="form-control" type="date" name="">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Quote No: </label>
                                    <input class="form-control" disabled type="text" name="">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Your Reference: </label>
                                    <input class="form-control" type="text" name="" placeholder="Your Reference">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Our Reference: </label>
                                    <input class="form-control" type="text" name="" placeholder="Our Reference">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Service Order terms and Conditions: </label>
                                <textarea class="form-control" rows="3" placeholder="Service Order terms and Conditions"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title: </label>
                                        <input class="form-control" type="text" name="" placeholder="Job Title">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Description:
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">Add</button>
                                        </label>
                                        <input class="form-control" type="text" name="" placeholder="Job Description">
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-2 form-group">
                                    <label>Price: </label>
                                    <input class="form-control" type="Number" name="" placeholder="Price">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Disc %: </label>
                                    <input class="form-control" type="Number" name="" placeholder="Disc %">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Freight %: </label>
                                    <input class="form-control" type="Number" name="" placeholder="Freight %">
                                </div>
                                <div class="col-2 form-group">
                                    <label>Select Account: </label>
                                    <select class="form-control" name="">
                                        <option disabled selected value>Select Account</option>}
                                        <option value=""></option>}
                                        option
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <label>Tax: </label>
                                    <select class="form-control" name="">
                                        <option value="">Yes</option>
                                        <option value="">No</option>
                                        option
                                    </select>
                                </div>
                            </div>

                            <div align="center">
                                <button class="btn btn-success btn3" type="submit">Add</button>
                            </div>

                            <br>
                            <hr>

                            <div class="table-header">
                                <p>Data List</p>
                            </div>

                            <table id="example" class="table table-striped table-bordered table-hover display table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>SN</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Disc %</th>
                                        <th>Freight</th>
                                        <th>Account</th>
                                        <th>Tax Rate</th>
                                        <th>Amount AUD</th>
                                        <th></th>
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

<!-- Modal Start -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Save a New Job Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Use Selected Job Description</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-group">
                            <label>Job Title: </label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <div class="form-group">
                            <label>Job Description: </label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <strong style="color:red">please click once it will be appear your Job Description space in the quote</strong>
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
