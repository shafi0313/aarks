{{-- <!-- Our Reference --> --}}
<div class="modal fade" id="ourReference" tabindex="-1" role="dialog" aria-labelledby="ourReferenceLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-info" id="ourReferenceLabel">Our Reference:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select id="ourRefSelect" class="form-control" onchange="ourRef(this.value)">
                    <option value selected disabled>SELECT ONE REFERENCE</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_ref }}">{{ $customer->name }} <==>
                                {{ $customer->customer_ref }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

{{-- <!-- Quote Terms --> --}}
<div class="modal fade" id="quote" tabindex="-1" role="dialog" aria-labelledby="quoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="false">Save a New Template</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="true">Use Selected Template</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                        {{-- Trams & Condition Store --}}
                        <form action="{{ route('quote.tmstore') }}" id="temStore" method="POST" autocomplete="off">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="type" value="1">
                            <div class="form-group">
                                <label>Template Title: <span style="color:red; font-size:10px">(Don't use quotation
                                        mark)</span></label>
                                <input class="form-control" type="text" name="title">
                            </div>
                            <div class="form-group">
                                <label>Template Details: <span style="color:red; font-size:10px">(Don't use quotation
                                        mark)</span></label>
                                <textarea class="form-control summer_note" name="details"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" style="width: 150px" class="btn btn-success">Save</button>
                            </div>
                        </form>
                        {{-- /Trams & Condition Store --}}
                    </div>
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <strong style="color:red">Please click once it will be appear your condition space in the
                            quote</strong>
                        <br>
                        <br>
                        <table class="table table-light">
                            <tbody class="temp-body">
                            </tbody>
                        </table>
                        {{-- Trams & Condition Edit/Show --}}
                        <div class="trms_edit" style="display: none">
                            <hr>
                            <p class="alert alert-success">Edit Trams & Condition</p>
                            <form action="{{ route('quote.tm.update') }}" method="POST" id="trams_update">
                                @csrf @method('POST')
                                <input type="hidden" name="type" value="1">
                                <input type="hidden" name="id" id="trms_edit_id">
                                <div class="form-group">
                                    <label for="Title" class="required">Title </label>
                                    <input type="text" name="title" class="form-control" id="trms_edit_title"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="details" class="required">Details </label>
                                    <textarea name="details" id="trms_edit_details" class="form-control" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" style="width: 150px"
                                        class="btn btn-warning">Update</button>
                                </div>
                            </form>
                        </div>
                        {{-- /Trams & Condition Edit/Show --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" style="width: 200px"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- <!-- Job And Description--> --}}
<div class="modal fade" id="job" tabindex="-1" role="dialog" aria-labelledby="jobLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="job-tab" data-toggle="tab" href="#jobHome" role="tab"
                            aria-controls="home" aria-selected="false">Save a New Job
                            Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="jobprofile-tab" data-toggle="tab" href="#jobprofile"
                            role="tab" aria-controls="profile" aria-selected="true">Use Selected Job
                            Description</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="jobHome" role="tabpanel" aria-labelledby="job-tab">
                        <form id="jobstore" action="{{ route('quote.jobstore') }}" method="post"
                            autocomplete="off">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="type" value="1">
                            <div class="form-group">
                                <label class="required">Job Title </label>
                                <span style="color:red; font-size:10px">(Don't use quotation mark)</span>
                                <input class="form-control" required type="text" name="title" value="">
                            </div>
                            <div class="form-group">
                                <label class="required">Job Description </label>
                                <span style="color:red; font-size:10px">(Don't use quotation mark)</span>
                                <textarea class="form-control" required name="details" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="required">Account Code </label>
                                <select name="client_account_code_id" id="ac_code" class="form-control" required>
                                    <option value selected disabled>Select Account Code</option>
                                    @foreach ($codes as $code)
                                        <option value="{{ $code->id }}">{{ $code->name }} &harr;
                                            {{ $code->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary btn200" id="jobstoreBtn">Save Job</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade show active" id="jobprofile" role="tabpanel"
                        aria-labelledby="jobprofile-tab">
                        <strong style="color:red">Please click once it will be appear your Job Description space in
                            the quote</strong>
                        <br>
                        <br>
                        <table class="table table-light">
                            <tbody class="job-body">
                            </tbody>
                        </table>
                        {{-- Job Edit/Show --}}
                        <div class="job_edit" style="display: none">
                            <hr>
                            <p class="alert alert-success">Edit Job Description</p>
                            <form action="{{ route('quote.jobUpdate') }}" method="POST" id="job_update">
                                @csrf
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <input type="hidden" name="job_id" id="job_id">
                                <div class="form-group">
                                    <label class="required">Job Title </label>
                                    <span style="color:red; font-size:10px">(Don't use quotation mark)</span>
                                    <input type="text" name="title" id="job_edit_title" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Job Description </label>
                                    <span style="color:red; font-size:10px">(Don't use quotation mark)</span>
                                    <textarea name="details" id="job_edit_details" cols="30" rows="10" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="required">Account Code </label>
                                    <select name="client_account_code_id" id="ac_code" class="form-control"
                                        required>
                                        <option id="current_code"></option>
                                        @foreach ($codes as $code)
                                            <option value="{{ $code->id }}">{{ $code->name }} &harr;
                                                {{ $code->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" style="width: 150px" id="job_update_btn"
                                        class="btn btn-warning">Update
                                        Job</button>
                                </div>
                            </form>
                        </div>
                        {{-- /Job Edit/Show --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- <!-- /Job And Description--> --}}

@include('frontend.sales.trams-condition-js')
@include('frontend.sales.job-js')

<script>
    $(document).ready(function() {
        $('.summer_note').summernote({
            height: 200,
        });
        $('#tearms_area').summernote({
            height: 180,
        });
    })

    function ourRef(value) {
        $(".ourRefInput").val(value);
        $("#ourReference .close").click();
    }

    function toast(status, header, msg) {
        // $.toast('Here you can put the text of the toast')
        Command: toastr[status](header, msg)
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
