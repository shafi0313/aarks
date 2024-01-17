<div class="hidden modal overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center"
    id="editRoomModal">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Room</h3>
                <button onclick="toggleModal('editRoomModal')" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="defaultModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form class="space-y-4 md:space-y-6" onsubmit="roomStore(event, this, 'editRoomModal')"
                    action="{{ route('calendar.rooms.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="roomId">
                    <div class="input-container">
                        <input type="text" class="input-field" name="name" id="roomName" required>
                        <label for="name" class="input-label">Room Name *</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="input-field" name="no_of_bed" id="no_of_bed">
                        <label for="no_of_bed" class="input-label">Number of Bed</label>
                    </div>
                    <div class="input-container">
                        <input type="text" class="input-field" name="description" id="roomDescription">
                        <label for="description" class="input-label">Description</label>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                        <button type="button" onclick="deleteRoom()"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editRoomModal(id, name, no_of_bed, description) {
        $('#roomId').val(id);
        $('#roomName').val(name);
        $('#no_of_bed').val(no_of_bed);
        $('#roomDescription').val(description);

        let modalID = 'editRoomModal';
        document.getElementById(modalID).classList.toggle("hidden");
        document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
        document.getElementById(modalID).classList.toggle("flex");
        document.getElementById(modalID + "-backdrop").classList.toggle("flex");
    }
</script>
