<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center"
        id="user-modal-id">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">User Information Update</h3>
                    <button onclick="toggleModal('user-modal-id')" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="defaultModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <form class="space-y-4 md:space-y-6" method="post" action="{{ url('user-update') }}">
                        @csrf

                        <div class="input-container">
                            <input type="hidden" id="userid" name="userid" required>
                            <input type="text" class="input-field" id="user_name" name="name" required>
                            <label for="user_name" class="input-label">Enter Name</label>
                        </div>

                        <div class="input-container">
                            <input type="email" class="input-field" id="user_email" name="email" required>
                            <label for="user_email" class="input-label">Enter Email</label>
                        </div>

                        <div class="input-container">
                            <input type="text" class="input-field" id="user_color_name" name="color_name"
                                required>
                            <label for="user_color_name" class="input-label">Enter Color Name</label>
                        </div>

                        <div class="input-container">
                            <input type="color" class="input-field w-2/2 h-11" id="user_color" name="color"
                                required>
                            <label for="user_color" class="input-label">Enter color code</label>
                        </div>

                        <div class="input-container">
                            <input type="text" class="input-field" id="user_calendar_id" name="calendar_id"
                                required>
                            <label for="user_calendar_id" class="input-label">Enter calendar id</label>
                        </div>

                        <div class="input-container">
                            <input type="text" class="input-field" id="user_password" name="password" required>
                            <label for="user_password" class="input-label">Enter Password</label>
                        </div>


                        <button type="submit"
                            class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Update
                            Info's</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
