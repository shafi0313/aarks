<script>
    function fetchRoom() {
        let apiEndpoint = '{{ route('calendar.rooms.index') }}';

        fetch(apiEndpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                let roomss = '';
                data.rooms.forEach(room => {
                    // Append each room's HTML to the existing content
                    roomss += `<p class="bg-orange-500 text-white text-center py-1"
                            style="color:white;cursor: pointer;" onclick="editRoomModal('${room.id}', '${room.name}', '${room.no_of_bed??''}', '${room.description??''}')">
                            ${room.name}</p>`;
                });
                document.getElementById('rooms').innerHTML = roomss;
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    fetchRoom();

    function roomStore(e, form, modal) {
        e.preventDefault();
        let formData = new FormData(form);
        $.ajax({
            url: $(form).attr("action"),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: (res) => {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message,
                }).then((confirm) => {
                    if (confirm) {
                        fetchRoom();
                        document.getElementById(modal).classList.toggle("hidden");
                        document.getElementById(modal + "-backdrop").classList.toggle(
                            "hidden");
                        document.getElementById(modal).classList.toggle("flex");
                        document.getElementById(modal + "-backdrop").classList.toggle("flex");
                        $(form).trigger("reset");
                    }
                });
            },
            error: (err) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: err.responseJSON.message,
                });
            },
        });
    }

    function deleteRoom() {
    let id = $('#roomId').val() || 1; // Use a default value if id is not present

    $.ajax({
        url: '{{ route('calendar.rooms.destroy') }}',
        type: 'POST',
        data: {
            _method: 'DELETE', // This is how Laravel fakes a DELETE request in a POST request
            id: id,
            _token: '{{ csrf_token() }}',
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message,
            }).then((confirm) => {
                if (confirm.isConfirmed) {
                    fetchRoom(); // Refresh the room list after deletion
                }
            });
        },
        error: function(err) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: err.responseJSON.message,
            });
        },
    });
}

</script>
