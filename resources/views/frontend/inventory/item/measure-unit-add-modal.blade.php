<!-- Modal -->
<form id="unit_manage_db" action="{{ route('inv_item.measure') }}" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="client_id" value="{{ $client->id }}">
    <input type="hidden" name="profession_id" value="{{ $profession->id }}">
    <div class="modal fade" id="buy_meaaure_myModal" tabindex="-1" role="dialog" aria-labelledby="measure">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="measure" style="color: red !important">Add Measure Unit
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unit Name</label>
                                <input type="text" name="name" id="unit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Unit Details</label>
                                <input type="text" name="details" id="unit_details" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success ">Save </button>
                </div>
            </div>
        </div>
</form>

<script>
    $(".unitmange").on('change', function() {
        var buy_measure_unit = $(this).val();
        if (buy_measure_unit == "0") {
            $('#buy_meaaure_myModal').modal('show');
        }
    });
    $("#unit_manage_db").submit(function(e) {
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        console.log(formURL);
        $.ajax({
            url: formURL,
            timeout: 1000,
            type: "POST",
            async: false,
            crossDomain: true,
            data: postData,
            success: function(res) {
                if (res.status == 200) {
                    let measure =
                        '<option value="" selected="" disabled="">--Select Measure Unit--</option> <option value="0">New Measure Unit</option>';
                    $.each(res.measures, function(i, v) {
                        measure += '<option value="' + v.id + '">' + v.name + '</option>';
                    });
                    $("#buy_measure_unit").html(measure);
                    $("#sell_measure_unit").html(measure);
                    $("#inv_measure_unit").html(measure);
                    $(".job_success").text('Template saved successfully completed.');
                    $("#unit_name").val("");
                    $('#unit_details').val("");
                    toast('success', res.message);
                    $('#buy_meaaure_myModal').modal('hide');
                } else {
                    toast('error', res.message);
                }
            },
            error: err => {
                toast('error', 'Error');
            }

        });
        e.preventDefault();
    });
</script>
