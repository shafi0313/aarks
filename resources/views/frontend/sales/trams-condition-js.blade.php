<script>
    // Terms Condition
    $("#temStore").on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        const url = $(this).attr('action');
        const method = $(this).attr('method');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: res => {
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

    function readData() {
        $.ajax({
            url: '{{ route('quote.tmShow') }}',
            method: 'get',
            success: res => {
                let tems = '';
                $.each(res.dedotrs, function(i, v) {
                    // tems +=
                    //     '<tr><td><a href="#" data-dismiss="modal" class="template_copy" data-tqd ="' +
                    //     v.details + '">' + v.title +
                    //     '</a></td><td width="5px"><a href="#" data-tq_id ="' + v.id +
                    //     '" data-tqt ="' + v.title +
                    //     '" data-tqd ="' + v.details +
                    //     '" class="btn_edit" id="' + v.id +
                    //     '"><i class="fa-regular fa-pen-to-square"></i></a></td><td width="5px"><a href="#" class="btn_remove" id="' +
                    //     v.id + '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td></tr>'

                    tems += `<tr>
                                <td>
                                    <a href="#" data-dismiss="modal" class="template_copy" data-tqd="${v.details}">
                                        ${v.title}
                                    </a>
                                </td>
                                <td width="5px">
                                    <a href="#" data-tq_id="${v.id}" data-tqt="${v.title}" data-tqd="${v.details}" class="btn_edit" id="${v.id}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </td>
                                <td width="5px">
                                    <a href="#" class="btn_remove" id="${v.id}">
                                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>`;

                });
                $(".temp-body").html(tems);
            }
        });
    }

    $(document).on('click', '.template_copy', function() {
        var tqd = $(this).data("tqd");
        $("#tearms_area").summernote('code', tqd);
    });

    // Trams & Condition Edit
    $(document).on('click', '.btn_edit', function() {
        $('.trms_edit').css('display', 'block');
        var tq_id = $(this).data("tq_id"); // Trams & Condition Id
        var tqt = $(this).data("tqt"); // Trams & Condition Title
        var tqd = $(this).data("tqd"); // Trams & Condition Details
        $("#trms_edit_id").val(tq_id);
        $("#trms_edit_title").val(tqt);
        $('#trms_edit_details').summernote("code", tqd);
    });

    // Update
    $("#trams_update").on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        const url = $(this).attr('action');
        const method = $(this).attr('method');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: res => {
                console.log(res);
                $('form').trigger('reset');
                $("#home-tab").removeClass('active');
                $("#profile-tab").addClass('active');
                $("#home").removeClass('active show');
                $("#profile").addClass('active show');
                toast('success', 'Trams & Condition Updated Successfully')
                readData();
                $('.trms_edit').css('display', 'none');
            }
        });
    });
    // !Trams & Condition Edit

    $(document).on('click', '.btn_remove', function() {
        var id = $(this).attr("id");
        if (confirm('Are you sure?') == true) {
            $.ajax({
                url: '{{ route('quote.tmDelete') }}',
                method: 'get',
                data: {
                    id: id
                },
                success: res => {
                    readData();
                }
            });
        } else {
            return false;
        }
    });
</script>
