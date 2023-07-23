<script>
    // Job Create
    $("#jobstoreBtn").on('click', function(e) {
        e.preventDefault();
        const data = $("#jobstore").serialize();
        const url = $("#jobstore").attr('action');
        const method = $("#jobstore").attr('method');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: res => {
                $('form').trigger('reset');
                $("#job-tab").removeClass('active');
                $("#jobprofile-tab").addClass('active');
                $("#jobHome").removeClass('active show');
                $("#jobprofile").addClass('active show');
                jobReadData();
            }
        });
    });

    function jobReadData() {
        $.ajax({
            url: '{{ route('quote.jobshow') }}',
            method: 'get',
            success: res => {
                let tems = '';
                $.each(res.jobs, function(i, v) {
                    tems += `<tr>
                                <td>
                                    <a href="#" data-dismiss="modal" class="job_copy" data-title="${v.title}" data-des="${v.details}" data-code_name="${v.code.name}" data-code="${v.code.code}" data-tax="${v.is_tax}">
                                        ${v.title}
                                    </a>
                                </td>                                
                                <td width="8px">
                                    <a href="#" class="job_edit_btn" data-job_id="${v.id}" data-job_title="${v.title}" data-job_details="${v.details}" data-code_id="${v.client_account_code_id}" data-code_name="${v.code.name}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </td>
                                <td width="8px">                                    
                                    <a href="#" class="text-danger job_btn_remove" id="${v.id}">
                                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>`;
                });
                $(".job-body").html(tems);
            }
        });
    }
    // Job Edit
    $(document).on('click', '.job_edit_btn', function() {
        $('.job_edit').css('display', 'block');
        const job_id = $(this).data('job_id');
        const job_title = $(this).data('job_title');
        const job_details = $(this).data('job_details');
        const code = $(this).data('code');
        const code_id = $(this).data('code_id');
        const code_name = $(this).data('code_name');
        $("#job_id").val(job_id);
        $("#job_edit_title").val(job_title);
        $("#job_edit_details").val(job_details);
        $("#current_code").val(code_id);
        $("#current_code").text(code_name);
    });
    // Update
    $("#job_update_btn").on('click', function(e) {
        e.preventDefault();
        const data = $("#job_update").serialize();
        const url = $("#job_update").attr('action');
        const method = $("#job_update").attr('method');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: res => {
                $('form').trigger('reset');
                $("#job-tab").removeClass('active');
                $("#jobprofile-tab").addClass('active');
                $("#jobHome").removeClass('active show');
                $("#jobprofile").addClass('active show');
                toast('success', 'Job Description Updated Successfully')
                jobReadData();
                $('.job_edit').css('display', 'none');
            }
        });
    });
    // /Job Edit
    $(document).on('click', '.job_copy', function() {
        $("#job_title").val($(this).data("title"));
        $("#job_des").val($(this).data("des"));
        $("#ac_code_name").val($(this).data("code_name"));
        $("#chart_id").val($(this).data("code"));
        $("#is_tax").val($(this).data("tax"));
    });
    $(document).on('click', '.job_btn_remove', function() {
        var id = $(this).attr("id");
        if (confirm('Are you sure?') == true) {
            $.ajax({
                url: '{{ route('quote.jobdelete') }}',
                method: 'get',
                data: {
                    id: id
                },
                success: res => {
                    jobReadData();
                }
            });
        } else {
            return false;
        }
    });
</script>
