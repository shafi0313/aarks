<!-- Ouer Referance -->
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
                    @foreach ($suppliers as $customer)
                    <option value="{{$customer->customer_ref}}">{{$customer->name}} <==> {{$customer->customer_ref}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Quote Terms -->
<div class="modal fade" id="quote" tabindex="-1" role="dialog" aria-labelledby="quoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{route('quote.tmstore')}}" id="temStore" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="type" value="2">
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Save a New Template</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Use Selected Template</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="form-group">
                                <label>Template Title: </label>
                                <input class="form-control" type="text" name="title">
                            </div>
                            <div class="form-group">
                                <label>Template Details: </label>
                                <textarea class="form-control" name="details" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <strong style="color:red">please click once it will be appaer your condition space in the
                                quote</strong>
                            <br>
                            <br>
                            <table class="table table-light">
                                <tbody class="temp-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Job And Description-->
<div class="modal fade" id="job" tabindex="-1" role="dialog" aria-labelledby="jobLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="jobstore" action="{{route('quote.jobstore')}}" method="post" autocomplete="off">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="type" value="2">
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="job-tab" data-toggle="tab" href="#jobHome" role="tab"
                                aria-controls="home" aria-selected="true">Save a New Job Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="jobprofile-tab" data-toggle="tab" href="#jobprofile" role="tab"
                                aria-controls="profile" aria-selected="false">Use Selected Job Description</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="jobHome" role="tabpanel" aria-labelledby="job-tab">
                            <div class="form-group">
                                <label>Job Title: </label>
                                <input class="form-control" required type="text" name="title" value="">
                            </div>
                            <div class="form-group">
                                <label>Job Description: </label>
                                <textarea class="form-control" required name="details" id="" cols="30"
                                    rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Account Code: </label>
                                <select name="client_account_code_id" id="ac_code" class="form-control" required>
                                    <option value selected disabled>SELETE ACCOUNT CODE</option>
                                    @foreach ($codes as $code)
                                    <option value="{{$code->id}}">{{$code->name}} &harr; {{$code->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="jobprofile" role="tabpanel" aria-labelledby="jobprofile-tab">
                            <strong style="color:red">please click once it will be appaer your Job Description space in
                                the quote</strong>
                            <br>
                            <br>
                            <table class="table table-light">
                                <tbody class="job-body">
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="jobstoreBtn" class="btn btn-primary">Save Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal End -->

<script>
    function ourRef(value){
        $(".ourRefInput").val(value);
        $("#ourReference .close").click();
    }
    // Terms Condition
    $("#temStore").on('submit',function(e){
        e.preventDefault();
        const data   = $(this).serialize();
        const url    = $(this).attr('action');
        const method = $(this).attr('method');
        $.ajax({
            url:url,
            method:method,
            data:data,
            success:res=>{
                console.log(res);
                $('form').trigger('reset');
                $("#home-tab").removeClass('active');
                $("#profile-tab").addClass('active');
                $("#home").removeClass('active show');
                $("#profile").addClass('active show');
                readData();
            }
        });
    });
    function readData(){
        $.ajax({
            url:'{{route("service_order.tmShow")}}',
            method:'get',
            success:res=>{
                let tems = '';
                $.each(res.dedotrs,function(i,v){
                    tems +='<tr><td><a href="#" data-dismiss="modal" class="template_copy"  data-tqd ="'+v.details+'">'+v.title+'</a></td><td width="5%"><a href="#" class="btn_remove" id="'+v.id+'"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td></tr>'
                });
                $(".temp-body").html(tems);
            }
        });
    }

    $(document).on('click', '.template_copy', function() {
        var tqd = $(this).data("tqd");
        $("#tearms_area").val(tqd);
    });
    $(document).on('click', '.btn_remove', function() {
        var id = $(this).attr("id");
        if(confirm('Are you sure?') == true){
            $.ajax({
                url:'{{route("quote.tmDelete")}}',
                method:'get',
                data:{id:id},
                success:res=>{
                    readData();
                    console.log(res);
                }
            });
        }else{
            return false;
        }
    });

    // Job Create
    $("#jobstoreBtn").on('click',function(e){
        e.preventDefault();
        const data   = $("#jobstore").serialize();
        const url    = $("#jobstore").attr('action');
        const method = $("#jobstore").attr('method');
        $.ajax({
            url:url,
            method:method,
            data:data,
            success:res=>{
                $('form').trigger('reset');
                $("#job-tab").removeClass('active');
                $("#jobprofile-tab").addClass('active');
                $("#jobHome").removeClass('active show');
                $("#jobprofile").addClass('active show');
                jobReadData();
            }
        });
    });
    function jobReadData(){
        $.ajax({
            url:'{{route("service_order.jobshow")}}',
            method:'get',
            success:res=>{
                let tems = '';
                $.each(res.jobs,function(i,v){
                    tems +='<tr><td><a href="#" data-dismiss="modal" class="job_copy" data-title="'+v.title+'" data-des="'+v.details+'" data-code_name="'+v.code.name+'" data-code="'+v.code.code+'" data-tax="'+v.is_tax+'">'+v.title+'</a></td><td width="5%"><a href="#" class="job_btn_remove" id="'+v.id+'"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td></tr>';
                });
                $(".job-body").html(tems);
            }
        });
    }
    // onclick="selectJob('+"'"+v.title+"',"+"'"+v.details+"',"+"'"+v.code.name+"',"+"'"+v.code.code+"',"+"'"+v.is_tax+"'"+')"

    // function selectJob(title,des,code_name,code,tax){
    //     $("#job_title").val(title);
    //     $("#job_des").val(des);
    //     $("#ac_code_name").val(code_name);
    //     $("#chart_id").val(code);
    //     $("#is_tax").val(tax);
    // }

    $(document).on('click', '.job_copy', function() {
        $("#job_title").val($(this).data("title"));
        $("#job_des").val($(this).data("des"));
        $("#ac_code_name").val($(this).data("code_name"));
        $("#chart_id").val($(this).data("code"));
        $("#is_tax").val($(this).data("tax"));
    });
    $(document).on('click', '.job_btn_remove', function() {
        var id = $(this).attr("id");
        if(confirm('Are you sure?') == true){
            $.ajax({
                url:'{{route("quote.jobdelete")}}',
                method:'get',
                data:{id:id},
                success:res=>{
                    jobReadData();
                    console.log(res);
                }
            });
        }else{
            return false;
        }
    });


</script>
