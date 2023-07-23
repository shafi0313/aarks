@extends('frontend.layout.master')
@section('title','Category')
@section('content')
<?php $p="tj"; $mp="payroll";?>
    <!-- Page Content Start -->
    <section class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row">

                                        <div class="form-group col-md-4">
                                            <label for="exampleInputName2" style="color:red;">Please Select employee *</label>
                                            <select class="form-control form-control-sm" name="empid" id="empid" required>
                                                <option value="">--Select employee--</option>
                                                <option value="161">Asraf Hasan</option>
                                                <option value="162">Kabir Hasan</option>
                                                <option value="165">Mahmudul Hasan</option>
                                                <option value="163">Murad Hasan</option>
                                                <option value="164">Naim Hasan</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail3" style="color:red;">Peroid Date *</label>
                                              <input type="text" class="form-control form-control-sm date-picker" data-date-format="dd/mm/yyyy" id="peroiddate" name="peroiddate" placeholder="dd/mm/yyyy" required>
                                              <strong class="datelock"></strong>
                                          </div>

                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">Taxable Gross Pay</label>
                                              <input type="text" class="form-control form-control-sm" id="taxable_gross_pay" name="taxable_gross_pay">
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">Annual Hours *</label>
                                              <input type="text" class="form-control form-control-sm" id="annualhousere" name="annualhousere">
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">Superannuation</label>
                                              <input type="text" class="form-control form-control-sm" id="superannuation" name="superannuation">
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">Income Tax</label>
                                              <input type="text" class="form-control form-control-sm" id="incometax" name="incometax">
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">CDEP Payments</label>
                                              <input type="text" class="form-control form-control-sm" id="cdep_payments" name="cdep_payments">
                                          </div>
                                          <div class="form-group col-md-4">
                                            <label for="inputEmail3">Long Service Leave</label>
                                              <input type="text" class="form-control form-control-sm" id="longserviceleave" name="longserviceleave">
                                          </div>
                                          <div class="col-md-4" style="padding-top:20px;">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>


                                </div>
                            </form>


                            <div class="table-header mt-3">
                                <p>Transation Journal</p>
                            </div>
                            <table id="example" class="table table-bordered table-hover display table-sm">
                                <thead class="text-center" style="font-size: 14px">
                                    <tr>
                                        <th>SN</th>
                                        <th>Employee name</th>
                                        <th>Peroid Date</th>
                                        <th>Gross Pay</th>
                                        <th>Annual Hours</th>
                                        <th>Superannuation</th>
                                        <th>Income Tax</th>
                                        <th>CDEP Payments</th>
                                        <th>Long Service Leave</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
                                        <td>f</td>
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
    <!-- Data Table -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
@stop
