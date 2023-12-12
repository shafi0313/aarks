<div style="padding-top:0px;">
    {{-- @if ($errors->any())
    <span class="text-danger">{{$errors->first()}}</span>
    @endif --}}
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <form id="amount_addForm" action="{{ route('dataStore.store') }}" method="post"
                    onsubmit="return validate()" autocomplete="off">
                    @csrf
                    <input type="hidden" name="clientId" value="{{ $client->id }} ">
                    <input type="hidden" name="professionId" value="{{ $profession->id }} ">
                    <input type="hidden" name="periodId" value="{{ $period->id }} ">
                    <input type="hidden" name="fyear" value="{{ $period->year }} ">
                    <input type="hidden" name="startDate" value="{{ $period->start_date }} ">
                    <input type="hidden" name="endDate" value="{{ $period->end_date }} ">
                    <input type="hidden" name="chartId" value="{{ $client_account->code }} ">
                    <input type="hidden" name="code_id" value="{{ $client_account->id }} ">
                    <input type="hidden" name="sub_cat_type" value="{{ $client_account->type }} ">
                    <input type="hidden" name="gst_method" value="{{ $client->gst_method }} ">
                    <input type="hidden" name="gst_code" value="{{ $client_account->gst_code }} ">
                    <input type="hidden" name="is_gst_enabled" value="{{ $client->is_gst_enabled }} ">
                    <td style="width:12%">
                        <input type="text" id="date" class="form-control" name="date" style="width:100%"
                            value="{{ bdDate($period->end_date) }}" TabIndex='1'>
                    </td>
                    <td style="width:10%">
                        <input type="number" step="any" id="amount" class="form-control"
                            value="{{ old('amount') }}" name="amount" style="width:100%" placeholder=" Amount $"
                            required TabIndex='2'>
                        <div id="msg"></div>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </td>
                    @if ($client_account->gst_code === 'GST' || $client_account->gst_code === 'CAP' || $client_account->gst_code === 'INP')
                        <td style="width:8%" id="gstfild">
                            <input class="gst form-control" step="any" type="number" id="gstamt"
                                value="{{ old('gstamt') }}" name="gstamt" style="width:100%" placeholder=" GST $"
                                TabIndex='3'>
                        </td>
                        <td style="width:9%" id="totalinvfild">
                            {{-- <div id="msginv" class=" text-danger"></div> --}}
                            <input class="totalinv form-control" type="text" id="totalinv" name="totalinv"
                                style="width:100%" step="any" placeholder=" T/Inv $" TabIndex='4'
                                value="{{ old('totalinv') }}">
                        </td>
                    @else
                        <td style="width:8%" id="gstfild">
                            <input class="gst form-control" step="any" type="number" id="gstamt" name="gstamt"
                                style="width:100%" value="{{ old('gstamt') }}" placeholder=" GST $" TabIndex='3'
                                disabled>
                        </td>
                        <td style="width:9%" id="totalinvfild">
                            {{-- <div id="msginv" class=" text-danger"></div> --}}
                            <input class="totalinv form-control" step="any" type="text" id="totalinv"
                                name="totalinv" style="width:100%" placeholder=" T/Inv $"
                                value="{{ old('totalinv') }}" TabIndex='4' disabled>
                        </td>
                    @endif
                    <td style="width:11%" id="balancefiled">
                        <input class="balance form-control" step="any" type="text" id="balance"
                            name="balance" style="width:100%" value="{{ old('balance') }}" placeholder=" Balance"
                            disabled>
                    </td>
                    <td style="width:10%">
                        <input type="text" id="percentile" step="any" name="percentile" style="width:100%"
                            class="form-control" value="{{ old('percentile') }}" placeholder=" %" TabIndex='5'>
                    </td>
                    <td style="width:35%">
                        <input type="text" required id="note" name="note" style="width:100%"
                            class="form-control" placeholder=" Note" TabIndex='6' value="{{ old('note') }}">
                        {{-- @error('note')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </td>
                    <td style="width:5%">
                        <button type="submit" class="valueempty_client width-100 btn btn-info btn-sm"
                            id="date_inv_submit" TabIndex='7'><i
                                class="fa fa-arrow-right-from-bracket"></i></button>
                    </td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="font-weight:16px; font-weight:800; color:red; border:1px solid white !important">
                <td colspan="2" style="border:1px solid white !important">Total Entry = {{ $data->count() }}</td>
                <td></td>
                <td></td>
                <td colspan="2"><strong>Total Balance :$ {{ $data->sum('balance') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="mgs"></div>
    </form>
</div>
@push('custom_scripts')
    <script>
        @if ($errors->any())
            toast('error', '{{ $errors->first() }}');
        @endif

        $('#amount_addForm input').on('keyup', function(e) {
            if (e.which == 13) {
                if ($(this).attr('TabIndex') < 7) {
                    e.preventDefault();
                } else {
                    $('[TabIndex=' + (+this.TabIndex + 1) + ']')[0].focus();
                }
            }
        });

        function validate() {
            var amount = document.forms["amount_addForm"]["amount"].value;
            var gst = document.forms["amount_addForm"]["gstamt"].value;
            var tinv = document.forms["amount_addForm"]["totalinv"].value;
            var percent = document.forms["amount_addForm"]["percentile"].value;
            var gstmult = gst * 11;

            // if(amoun != '' && gst ==''){
            //   alert("All Fild are Empty");
            //   return false;
            // }
            if (gst != "" && tinv == '') {
                $("#totalinv").attr('required');
            }
            if (tinv != "" && gst == "") {
                $("#gstamt").attr('required');
                alert("GST Field Required");
                return false;
            }
            if (gst != '' && gst < 0 && tinv > 0) {
                alert("Use (-) Sing in T/Inv");
                return false;
            }
            if (gst <= 0 && gst != '' && gstmult <= tinv) {
                alert("Bigger than " + gstmult);
                return false;
            }
            if (gst > 0 && gst != '' && tinv <= gstmult) {
                alert("T/Inv Must be Bigger than " + gstmult);
                return false;
            }
            $("#date_inv_submit").attr('disabled', 'disabled');
        }
        $("#totalinv").on('keyup', function(e) {
            let gstamt = $("#gstamt").val();
            let totalinv = $("#totalinv").val();
            let inv = gstamt * 11;
            if (totalinv < inv) {
                $("#msginv").html('T/Inv must be bigger than ' + inv);
            }
            if (totalinv >= inv || totalinv == '') {
                $("#msginv").html('');
            }
        });
        $("#amount").on('keyup', function(e) {
            $("#balance").attr('disabled', 'disabled');
            $("#totalinv").attr('disabled', 'disabled');
            $("#gstamt").attr('disabled', 'disabled');
            if ($("#amount").val() == '') {
                $("#gstamt").removeAttr('disabled', 'disabled');
                $("#balance").removeAttr('disabled', 'disabled');
                $("#totalinv").removeAttr('disabled', 'disabled');
            }
        });
        $("#gstamt").on('keyup', function(e) {
            $("#balance").attr('disabled', 'disabled');
            $("#amount").attr('disabled', 'disabled');
            if ($("#gstamt").val() == '') {
                $("#balance").removeAttr('disabled', 'disabled');
                $("#amount").removeAttr('disabled', 'disabled');
            }
        });
        $(".valueempty_client").on('click', function() {
            var amount = $("#amount").val();
            var gstamt = $("#gstamt").val();
            var totalinv = $("#totalinv").val();
            if (amount == '') {
                if (gstamt == '') {
                    alert("Amount Can Not be empty!");
                    $("#amount").focus();
                    return false;
                } else {}
            }
        });
        $('.delete').click(function() {
            var answer = confirm("Are you sure delete this data?");
            if (answer) {
                return true;
            } else {
                return false;
            }
        });
    </script>
@endpush
