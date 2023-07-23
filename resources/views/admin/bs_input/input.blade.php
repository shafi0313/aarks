@extends('admin.layout.master')
@section('title','Input Bank Statement')
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs" style="z-index: -1">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li>
                        <a href="#">Add/Edit Data</a>
                    </li>
                    <li>
                        <a href="#">Input Bank Statement</a>
                    </li>

                    <li>
                      <?php
                        if($client->first_name!=""){
                          echo '<a style="color:red;">Name: '.$client->first_name.'</a>';
                        }else{
                          echo '<a style="color:red; font-weight:bold;">Company: '.$client->company.'</a>';
                        }
                      ?>

                    </li>
                    <li>
                        <a href="#">{{$profession->name}}</a>
                    </li>
                    <li class="active">Input Bank Statement</li>
                </ul><!-- /.breadcrumb -->
            </div>
            <div class="page-content">

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                          <div style="margin: 5px">
                              <div class="row">
                                  <div class="col-md-2">
                                      Select Bank Account: 
                                  </div>
                                  <div class="col-md-3">
                                      <select class="form-control" name="" id="bank_account" @error('bank_account') style="border: 1px solid red;" @enderror>
                                          <option>Select Bank Account</option>
                                          @foreach($liquid_asset_account_codes as $liquid_asset_account_code)
                                            <option value="{{$liquid_asset_account_code->id}}" data-chart_id="{{ $liquid_asset_account_code->code }}">{{$liquid_asset_account_code->name}}</option>
                                          @endforeach
                                      </select>
                                    @error('bank_account')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                  </div>
                                  <div class="col-md-3">
                                    <p style="color: green; font-size: 22px"><span id="bank_code_name"></span> Balance: <span id="bank_balance"></span></p>
                                  </div>
                                  <div class="col-md-3">
                                    <p style="color: #8A6641; font-size: 22px">Current Balance: <span id="current_bank_balance"></span></p>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-md-12">
                                      <span>Entering Account Name:  </span>
                                  </div>
                                  <div class="col-md-12">
                                      <style>
                                          table,td,tr,th{
                                              border: 1px solid #dfe3eb;
                                              padding: 0;
                                              margin: 0;
                                          }
                                          .form-control{
                                              padding: 0;
                                              margin: 0;
                                          }
                                          .tbl { width: 100%; }
                                        .tbl tr td,.tbl tr th { padding: 4px 8px;}
                                      </style>

                                      <table class="tbl">
                                          <tr style="">
                                              <th class="center" style="width: 10%;">A/c Code</th>
                                              <th class="center" style="width: 10%;">Trn. Date</th>
                                              <th class="center" style="width: 25%;">Narration</th>
                                              <th class="center" style="width: 6%;">Tx Code</th>
                                              <th class="center">Debit</th>
                                              <th class="center">Credit</th>
                                              <th class="center">(Excl Tax)</th>
                                              <th class="center">Tax</th>
                                              <th class="center">Action</th>
                                          </tr>
                                          <tr>
                                              <td>
                                                  <select class="form-control is-invalid " name="" id="account_code" required>
                                                      <option value="">Select Bank Account First</option>
                                                  </select>
                                              </td>
                                              <td>
                                                  <input class="form-control datepicker" id="date" type="text" required autocomplete="off">
                                              </td>
                                              <td>
                                                  <input class="form-control" id="narration" type="text" required>
                                              </td>
                                              <td>
                                                  <select name="" id="gst_code" style="width: 100%; appearance:none;-moz-appearance: none;"  disabled>
                                                      @foreach(aarks('gst_code') as $gst_code)
                                                        <option value="{{ $gst_code }}">{{$gst_code}}</option>
                                                      @endforeach
                                                  </select>
                                              </td>
                                              <td>
                                                  <input id="debit" class="form-control" type="number" disabled>
                                              </td>
                                              <td>
                                                  <input id="credit" class="form-control" type="number" disabled>
                                              </td>
                                              <td>
                                                  <input id="exTax" class="form-control" type="text" disabled>
                                              </td>
                                              <td>
                                                  <input id="tax" class="form-control" type="text" disabled>
                                              </td>
                                              <td class="center">
                                                  <button id="add" class="btn btn-sm btn-secondary">Add</button>
                                              </td>
                                          </tr>
                                      </table>
                                  </div>
                                  <div class="col-md-12">
                                      <form action="" style="margin-top: 50px;">
                                          <table class="tbl" style="">
                                              <tr style=" color:#fff;" class="bg-primary">
                                                  <th class="center" style="width: 20%;">A/c Code</th>
                                                  <th class="center" style="width: 10%;">Trn. Date</th>
                                                  <th class="center" style="width: 25%;">Narration</th>
                                                  <th class="center" style="width: 6%;">Tx Code</th>
                                                  <th class="center">Debit</th>
                                                  <th class="center">Credit</th>
                                                  <th class="center" style="width: 5%;">Action</th>
                                              </tr>
                                              <tbody id="result">
                                              @foreach($inputs as $input)
                                                  <tr id="input_row_{{$input->id}}">
                                                      <td>{{$input->client_account_code->name}}</td>
                                                      <td>{{ $input->date->format(aarks('frontend_date_format')) }}</td>
                                                      <td>{{ $input->narration }}</td>
                                                      <td>{{ $input->gst_code }}</td>
                                                      <td style="text-align: right">{{ number_format($input->debit,2) }}</td>
                                                      <td style="text-align: right">{{ number_format($input->credit,2) }}</td>
                                                      <td class="center">
                                                          <a title="Bankstatement Delete" class="fa fa-trash-o bigger-130 red"  style="cursor: pointer" onclick="confirm('Are you sure to delete?') ? deleteBankStatement('{{$input->id}}') : ''"></a>
                                                      </td>
                                                  </tr>
                                              @endforeach
                                              </tbody>
                                          </table>
                                      </form>
                                      <br>
                                  </div>

                                  <div class="col-md-12">
                                    <form action="{{route('bank-statement.post')}}" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" name="bank_account" id="post_bank_account">
                                        <input type="hidden" name="client_id" value="{{$client->id}}">
                                        <input type="hidden" name="profession_id" value="{{$profession->id}}">
                                        <button type="submit" class="btn btn-danger" style="float: right; "> Post</button>
                                    </form>
                                </div>
                              </div>
                          </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <!-- Modal -->


    <script>
        $('#bank_account').change(function () {
            const chart_id = $(this).find(":selected").data('chart_id');
            const bank_account_name = $(this).find(":selected").text();
            $('#bank_code_name').text(bank_account_name);
            
            ledgerBalance(chart_id);
            // A/C Code            
            $('#post_bank_account').val($(this).val());
            $.ajax({
                url:"{{route('bs_input.getcodes')}}",
                data:{client:"{{$client->id}}",profession:"{{$profession->id}}",id:$(this).val()},
                method:'get',
                success:res=>{
                    let opt = '<option disabled selected> --- </option>';
                    if(res.status == 200){
                    $.each(res.codes,function(i,v){
                        opt += '<option value="'+v.id+'" data-gst="'+v.gst_code+'" data-type="'+v.type+'" data-code="'+ parseInt(v.code/100000) +'">'+v.name+' => '+v.code+'</option>'
                    });
                    $("#account_code").html(opt);
                    }else{
                        toast('error', 'No Codes found')
                    }
                },
                error:err=>{
                    toast('error', 'No Codes found')
                }
            });
        });

        function deleteBankStatement(id) {
            var request = $.ajax({
                url: "{{route('bank-statement.delete')}}",
                method: "GET",
                data: {id : id, client_id: '{{$client->id}}'},
            });

            request.done(function( response ) {
            alert('Successfully Deleted');
            $('#input_row_'+id).hide();
            });

            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus + jqXHR.responseText );
            });
        }

        function openField(){
            let debit  = $('#debit').val();
            let credit = $('#credit').val();
            if (debit == '' && credit == ''){
                $('#debit').prop('disabled',false);
                $('#credit').prop('disabled',false);
            }
            if (!debit == ''){
                $('#credit').prop('disabled',true);
            }
            if(!credit == ''){
                $('#debit').prop('disabled',true);
            }

        }
        $('#debit,#credit').keyup(function () {
            let ac_group = $('#account_code option:selected').data('code');
            if(ac_group == 9 || ac_group == 5) {
                openField();
            }
            let gst_code = $('#gst_code').val();
            let tax = $(this).val() && (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') ? parseFloat($(this).val())/11 : 0;
            let exTax = $(this).val() ? parseFloat($(this).val())-tax : 0;
            $('#tax').val(tax.toFixed(2));
            $('#exTax').val(exTax.toFixed(2));
        });

        $('#account_code').change(function () {
            let type = $('#account_code option:selected').data('type');
            let gst_code = $('#account_code option:selected').data('gst');
            let ac_group = $('#account_code option:selected').data('code');
            $("#gst_code option").each(function()
            {
                if($(this).val() == gst_code){
                    $(this).prop('selected', true);
                }
            });
            if(ac_group == 9 || ac_group == 5) {
                $('#debit').prop('disabled',false);
                $('#credit').prop('disabled',false);
            } else {
                if(type == 1){
                    $('#debit').prop('disabled',false);
                    $('#credit').prop('disabled',true);
                }else{
                    $('#debit').prop('disabled',true);
                    $('#credit').prop('disabled',false);
                }
            }
        });

        $('#add').click(function () {
            const chart_id = $('#bank_account').find(":selected").data('chart_id');
            var data = getData();
            var request = $.ajax({
                url: "{{route('bank-statement.store')}}",
                method: "GET",
                data: data,
            });

            request.done(function( response ) {
                let body = `<tr id="input_row_${response.id}">`+
                                '<td style="width: 20%;">'+response.client_account_code.name+'</td>'+
                                '<td>'+moment(response.date).format("DD/MM/YYYY")+'</td>'+
                                '<td>'+response.narration+'</td>'+
                                '<td style="width: 10%;">'+response.gst_code+'</td>'+
                                '<td class="debit" style="text-align: right">'+parseFloat(response.debit).toFixed(2)+'</td>'+
                                '<td class="credit" style="text-align: right">'+parseFloat(response.credit).toFixed(2)+'</td>'+
                                '<td class="center">'+
                                `<a class="fa fa-trash-o bigger-130 red" onclick="confirm('Are you sure to delete?') ? deleteBankStatement(${response.id}) : '' "></a>`+
                                '</td>'+
                            '</tr>';
                $('#result').append(body);
                ledgerBalance(chart_id)
                clear();
                toast('success', 'Bank Statement Input Added');
            });

            request.fail(function( jqXHR, textStatus, errorThrown) {
            //   alert( "Request failed: " + textStatus + jqXHR.responseText );
                toast('error', jqXHR.responseText);
            });
        });

        const ledgerBalance = (chart_id) => {            
            $.ajax({
                url:"{{route('bs_input.getBalance')}}",
                data:{client:"{{$client->id}}", profession:"{{$profession->id}}", chart_id:chart_id},
                method:'get',
                success:res=>{
                    if(res.status == 200){
                        $('#bank_balance').text(res.balance);
                        $('#current_bank_balance').text(res.current_balance);
                    }else{
                        $('#bank_balance').text(0);
                        $('#current_bank_balance').text(0);
                    }
                },
                error:err=>{
                    toast('error', 'Balance not found')
                }
            });
        }

        function getData() {
            return {
                'account_code': $('#account_code').val(),
                'narration' : $('#narration').val(),
                'date' : $('#date').val(),
                'debit': $('#debit').val(),
                'credit': $('#credit').val(),
                'client_id': '{{ $client->id }}',
                'profession_id': '{{ $profession->id }}',
                'gst_code': $('#gst_code').val(),
            };
        }

        function clear() {
            $('#account_code').val('');
            $('#narration').val('');
            $('#date').val('');
            $('#debit').val('');
            $('#credit').val('');
            $('#gst_code').val('');
            $('#exTax').val('');
            $('#tax').val('');
        }
    </script>

    <script>
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });
    </script>
@stop

