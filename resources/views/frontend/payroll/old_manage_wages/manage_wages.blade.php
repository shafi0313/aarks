@extends('frontend.layout.master')
@section('title','Manage Wages')
@section('content')

    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="t_b">Name: </label><strong class="t_red">*</strong>
                                <input class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label for="" class="t_b">Type: </label><strong class="t_red">*</strong>
                                <select class="form-control form-control-sm" name="" id="">
                                    <option value="Salary">Salary</option>
                                    <option value="Hourly">Hourly</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="regularrate" id="regularrate" value="regular">
                                            Regular Rate Multiplied by:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" type="text"  name="regularrateval" id="regularrateval">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="regularrate" id="regularrate" value="fixed">
                                            Fixed Hourly Rate of:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" type="text" name="fixedhourlyval" id="fixedhourlyval">
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

                            <div class="card-heading mt-5">
                                <p></p>
                            </div>
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Wages Name</th>
                                        <th>Wages Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Base Salary</td>
                                        <td>Salary</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Base Hourly</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Meal Allowence General (Per Meal)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>General Allowence</td>
                                        <td>Salary</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Travel Allowence (Per KM)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Reportable Fringe Benefit</td>
                                        <td>Salary</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Annual Leave Pay</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Back Pay</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Bonus</td>
                                        <td>Salary</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Sunday Rate (Standard)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Public Holiday (Standard)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Penalty rate after six PM (standard)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Saturday (Standard)</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Personal Leave Pay</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>CDEP Payments</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>unused Long Service Leave</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Meal Allowance</td>
                                        <td>Hourly</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Remuneration</td>
                                        <td>Salary</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content End -->

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
