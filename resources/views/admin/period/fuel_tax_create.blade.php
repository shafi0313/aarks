<div class="col-md-12">
    <div class="row" style="background:#2894FF; color:white; padding:3px; font-size:18px;" align="center">
        <div class="">
            <h4 style="display: inline-block; float: left;">
                <span class="label label-lg label-pink arrowed-right" style="font-weight: 900; ">
                    Add PAYG Installment ( Check BAS Paper from ATO/ Ask Tax Agent)
                </span>
                <span class="label label-lg label-inverse"
                    style="font-weight: 900; cursor: pointer; color: black; background: orange;" data-toggle="modal"
                    data-target="#myModal_2" id="paygbtn">
                    <i class="ace-icon glyphicon glyphicon-plus-sign"></i> Add PAYG
                </span>
            </h4>
            <div style="float: right;">
                <h4 style="display: inline-block; float: left;">
                    <span class="label label-lg label-inverse "
                        style="font-weight: 900; cursor: pointer; color: black; background: orange;" data-toggle="modal"
                        data-target="#myModal">
                        <i class="ace-icon glyphicon glyphicon-plus-sign"></i> Add Fuel tax Credit
                    </span>&nbsp;
                    <span class="label label-lg label-inverse arrowed-in arrowed-right">
                        Total = $ {{ $fuelLtr->sum('amount') }} </span>
                    <span class="label label-lg label-inverse arrowed-in arrowed-right">
                        Total LTR = ${{ $fuelLtr->sum('ltr') }}</span>&nbsp;
                </h4>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top:10px;">
        <table width="100%" border="1">
            <tr>
                <td width="58%" align="center" style="color:green; font-size:20px;">
                    Total GST payble calculation(including PAYG): </td>
                <td width="18%" align="center" style="font-size:20px;">
                    $ @if ($payable)
                        {{ $payable->payable }}
                    @endif
                </td>
                <td width="24%">
                    <button class="btn btn-info">Recalculate</button>
                </td>
            </tr>
        </table>
    </div>
    <div class="row" style="border:1px solid #999999; padding:5px; color:#4f0099; font-size:20px;" align="center">
        Profit and Loss Statement :
    </div>
    <div class="row">
        <table width="100%" border="1">
            <tr>
                <td height="35" align="center" valign="middle" style="color:#4f0099; font-size:15px;">Total Income:
                </td>
                <td align="center" valign="middle" style="color:#4f0099; font-size:15px;">$</td>
                <td align="center" valign="middle" style="color:#4f0099; font-size:15px;">$</td>
                <td align="center" valign="middle" style="color:#4f0099; font-size:15px;">Total Expense: </td>
                <td align="center" valign="middle" style="color:#4f0099; font-size:15px;">$</td>
                <td align="center" valign="middle" style="color:#4f0099; font-size:15px;">$</td>
            </tr>
            <tr>
                <td height="34" colspan="6" align="center" valign="middle" style="color:#4f0099; font-size:15px;">
                    Net Profit (Loss) before GST:
                    <strong style="color:green;">$</strong> &nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;
                    Net Profit (Loss) after GST :
                    <strong style="color:green;"></strong>
                </td>
            </tr>
        </table>
    </div>
    <div class="row" style="border:1px solid #CCCCCC; min-height:150px;">
        <p style="padding:10px;">
            <b>Electronic Lodgment Declaration: </b><br />
            This declaration is to be completed where a taxpayer elects to use an
            approved ATO electronic channel. It is the responsibility of the
            taxpayer to retain this declaration for a period of five years after the
            declaration is made, penalties may apply for failure to do so. <br />
            <b>Privacy: </b><br />
            The ATO is authorised by the Taxation Administration Act 1953 to request
            your tax file number (TFN). We will use your TFN to identify you in our
            records. It is not an offence not to provide your TFN. However, you
            cannot lodge your income tax form electronically if you do not quote
            your TFN. Taxation law authorises the ATO to collect information and to
            disclose it to other government agencies. For information about your
            privacy go to ato.gov.au/privacy <br />
            <b>Electronic Funds Transfer - Direct Debit:</b><br />
            Where you have requested an EFT direct debit some of your details will
            be provided to your financial institution and the Tax Office's sponsor
            bank to facilitate the payment of your taxation liability from your
            nominated account <br />
            <b>Important:</b><br />
            The tax law imposes heavy penalties for giving false or misleading
            information. <br />
            <b>I Declare That:</b>
            the information provided to my registered tax agent for the preparation
            of this tax return, including any applicable schedules is true and
            correct, and the agent is authorised to lodge this TAX / BAS / GST
            return.<br />
            <b>Tax file number : </b> ABN :{{ $client->abn_number }} , Year : {{ $period->year }} Period :
            {{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }}
        </p>
        <button class="btn btn-primary pull-right btn-lg" disabled>Submit</button>
    </div>
</div>

{{-- MODAL --}}

<!-- Fuel Tax Model -->
<div id="myModal" class="modal fade" tabindex="-1" data-backdrop="static">
    <form id="fueltaxform" action="{{ route('fuel.store') }} " method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="rate" id="fuel_rate"
            value="@if ($fuel) {{ $fuel->rate ?? 0 }} @endif">
        <div class="modal-dialog" style="width:600px; z-index:1000000000;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <i class="fa-solid fa-triangle-exclamation text-danger" style="font-size: 25px"
                        title="Before FTC entering please check the FTC rate"></i>
                    <h4 class="modal-title" align="center">
                        <span class="label label-xlg label-primary arrowed-right">
                            Current Rate: @if ($fuel)
                                {{ $fuel->rate ?? 0 }}
                            @endif
                        </span>
                        <span class="label label-xlg label-pink arrowed-in arrowed-in-right">Fuel
                            Tax Credit</span>
                        <span class="label label-xlg label-primary arrowed">Date:
                            {{ bdDate($period->end_date) }}
                        </span>
                    </h4>
                </div>
                <div class="modal-body">
                    <!--- form vertical ---->
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="exampleInputName2">Date</label>
                            <input type="" class="form-control" value="{{ bdDate($period->end_date) }}"
                                name="date" id="datepicker" autocomplete="off">
                            <small id="taxMsg" style="display: none;color: red">Message</small>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="exampleInputName2">LTR.</label>
                            <input type="text" class="form-control" id="ltr" name="ltr"
                                placeholder="LTR...">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group" style="padding-top:25px;">
                            <input type="hidden" name="clientId" value="{{ $client->id }} ">
                            <input type="hidden" name="periodId" value="{{ $period->id }} ">
                            <input type="hidden" name="profession_id" value="{{ $profession->id }} ">
                            <input type="hidden" id="startDate"
                                value="{{ \Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }} ">
                            <input type="hidden" id="endDate"
                                value="{{ \Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }} ">
                            <button type="submit" class="ltrvalidate btn btn-primary savebtn"
                                id="ltrBtn">Submit</button>
                        </div>
                    </div>
                    <div id="data_info_clinet" align="center">
                        <table class="table table-striped table-bordered table-hover item-table itrTable">
                            <thead>
                                <tr>
                                    <th>Date </th>
                                    <th>LTR </th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Amount: </td>
                                    <td>Total LTR: </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /Fuel Tax Model -->
<!-- Add Payg Modal -->
<form id="payg_form" action="{{ route('payg.store') }} " method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="myModal_2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal"
                        aria-hidden="true">×</button>
                    <h4 class="modal-title" align="center">
                        <span class="label label-xlg label-primary arrowed-right">
                            -----------------------
                        </span>
                        <span class="label label-xlg label-pink arrowed-in arrowed-in-right">Add PAYG
                            Installment</span>
                        <span class="label label-xlg label-primary arrowed">{{ bdDate($period->end_date) }}</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="bootbox-body">
                        <div align="center" class="loading" id="loader3" style="display:none; z-index: 10000000;">
                            <img src="http://aarks.net/upload/loading.gif" height="150" />
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="hidden" name="clientId" value="{{ $client->id }} ">
                                    <input type="hidden" name="periodId" value="{{ $period->id }} ">
                                    <label for="exampleInputName2">Percent</label>
                                    <input type="text" class="form-control payg_percenttige"
                                        name="payg_percenttige" id="payg_percenttige" required="required"
                                        value="" placeholder="Enter Percentage">
                                </div>
                            </div>
                            <div class="col-sm-1" style="padding-top: 30px;">
                                OR
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="exampleInputName2">Amount</label>
                                    <input type="text" class="form-control payg_amount" id="payg_amount"
                                        name="payg_amount" placeholder="Enter Amount" required="required"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"
                    aria-hidden="true">Cancel</button>
                <button type="Submit" class="btn btn-success btn-sm ">Submit</button>
            </div>
        </div>
    </div>
</form>
