<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focus Taxation & Accounting Calendar </title>
    <link rel="icon" type="image/x-icon" href="{{ url('fav.jpg') }}">
    <!-- Fonts -->

    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    {{-- <!-- Styles --> --}}
    <link href="https://unpkg.com/tailwindcss@0.3.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('calendar/style.css') }}">

</head>

<body class="antialiased">
    <section class="bg-gray-50 dark:bg-gray-900 ">
        <div class="flex gap-4 text-white text-sm font-bold font-mono leading-6 rounded-lg">
            <div class="w-14 h-14 flex-none rounded-lg flex items-center justify-center "></div>
            {{-- <div class="p-4 grow rounded-lg flex items-center justify-center ">
                <h3 onclick="window.location.href = '{{ route('calendar.home') }}';"
                    class="font-bold leading-tight text-center tracking-tight text-blue-900 md:text-2xl dark:text-white"
                    style="color:orange; cursor:pointer;">
                    <u> Focus Taxation & Accounting Calendar </u>
                </h3>
            </div> --}}
            <div class="flex-none rounded-lg flex items-center justify-center b">
                {{-- @if (Auth::user()->email == 'focustaxationwa@gmail.com')
                    <button onclick="toggleModal('setting-modal')"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 "><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg></button>
                @endif --}}
                {{-- <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 "><svg
                        class="h-6 w-6 text-white-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                        <line x1="12" y1="2" x2="12" y2="12" />
                    </svg></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form> --}}
            </div>
        </div>
        <button onclick="toggleModal('addRoomModal')" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-8 ml-8">Add
            Room</button>

        <div class="hidden sm:block grid grid-cols-1 gap-1 md:grid-cols-3">
            <div class="md:col-span-3 mt-4">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 lg:grid-cols-10"
                    style="background:#ac725e;color:white;" id="rooms">
                    {{-- @foreach ($rooms as $room)
                        <p class="bg-orange-500 text-white text-center py-1"
                            style="color:white;cursor: pointer;">
                            {{ $room->name }} </p>
                    @endforeach --}}
                    <span></span>
                </div>
            </div>
        </div>

        <div class="col-span-1 mt-4">
            <div class="bg-white-900">
                <div style="margin-top: 0px; background: white;">
                    <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-1 pt-5">
            <div class=" ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
                    <div
                        class="section  w-full text-sm text-center text-gray-500 dark:text-gray-400   flex items-center justify-between ">
                        <button onclick="toggleModal('modal-id')"
                            class="block md:hidden py-2 px-2 inline-flex justify-center items-center gap-2 rounded-full border border-transparent font-semibold bg-green-500 text-white hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Add Event
                        </button>
                        <h3
                            class="py-2 px-2 inline-flex justify-center items-center gap-2 rounded-full border border-transparent font-semibold bg-purple-500 text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                            Schedule List</h3>

                        <a href="{{ route('sync-calender') }}"
                            class="py-2 px-2 inline-flex justify-center items-center gap-2 rounded-full border border-transparent font-semibold bg-blue-500 text-white hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                            Synchronize Google Calendar
                        </a>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="flex items-center justify-center bg-green">
                            <input type="text" id="search-input" class="px-4 py-2 w-80" placeholder="Search...">
                            <button type="button" id="search-button"
                                class="flex items-center justify-center px-4 border-l">
                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <table
                        class="hidden sm:block section overflow-scroll scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-200 w-full text-sm text-center text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="">

                                <th scope="col" class="px-6 py-3" align="left">
                                    Meeting Description
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Phone Number
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Start Time
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    End Time
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    SMS Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_list as $v)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                    <th scope="row" class="" align="left">

                                        <div class="">
                                            <div class="text-base font-semibold">{{ $v['summmery'] }} ~~~
                                                @php echo $v['description']; @endphp</div>
                                        </div>
                                    </th>

                                    <td class="">
                                        <div class="flex items-center">
                                            {{ $v['phone'] }}
                                        </div>
                                    </td>
                                    <td class="">
                                        <div class="flex items-center">
                                            {{ $v['location'] }}
                                        </div>
                                    </td>

                                    <td class="">
                                        <div class="flex items-center">
                                            {{ $v['startdatetime'] }}
                                        </div>
                                    </td>

                                    <td class="">
                                        <div class="flex items-center">
                                            {{ $v['enddatetime'] }}
                                        </div>
                                    </td>

                                    <td class="">
                                        <div class="flex items-center">
                                            @if ($v['status'] == 0)
                                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> Pending
                                            @else
                                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Sent
                                            @endif

                                        </div>
                                    </td>

                                    <td align="center" class=" ">
                                        <div class="flex items-center">
                                            <!-- <a  href="{{ url('edit/' . $v->calender_id) }}"  class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                              <svg class="h-5 w-5 text-yellow-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />  <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" /></svg>
                              </a>    -->
                                            <a href="{{ url('delete/' . $v->calender_id) }}"
                                                class="font-medium text-danger-600  dark:text-blue-500 hover:underline">
                                                <svg class="h-5 w-5 text-red-600" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" />
                                                    <line x1="4" y1="7" x2="20"
                                                        y2="7" />
                                                    <line x1="10" y1="11" x2="10"
                                                        y2="17" />
                                                    <line x1="14" y1="11" x2="14"
                                                        y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $all_list->links() }}
                    <br>
                </div>
            </div>
        </div>
    </section>


    @include('calendar.modals.add-room-modal')
    @include('calendar.modals.edit-room-modal')
    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="user-modal-id-backdrop"></div>
    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="modal-id-backdrop"></div>
    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="addRoomModal-backdrop"></div>
    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="editRoomModal-backdrop"></div>
    @include('calendar.room-js')

    <script type="text/javascript">
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
            document.getElementById(modalID).classList.toggle("flex");
            document.getElementById(modalID + "-backdrop").classList.toggle("flex");
        }
    </script>
    @php
        // $setting = DB::table('settings')
        //     ->where('id', 1)
        //     ->first();

        $setting = 'des';
    @endphp
    @include('calendar.modals.setting-modal')




    <!-- Event Details Modal -->
    <div id="eventDetailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-8 w-96 rounded-lg shadow-lg">
            <h5 class="text-xl font-bold mb-4" id="eventTitle"></h5>
            <p class="mb-2" id="eventStart"></p>
            <p class="mb-2" id="eventEnd"></p>
            <p class="mb-2" id="eventPhone"></p>
            <p class="mb-2" id="eventLocation"></p>
            <p class="mb-4" id="eventDescription"></p>
            <p class="mb-4" id="eventCalender_id"></p>
            <button class="px-4 py-2 bg-green-500 text-white rounded" id="closeModal2">Edit</button>
            <button class="px-4 py-2 bg-red-500 text-white rounded delete-button" id="delete_event"
                data-val="">Delete</button>
            <button class="px-4 py-2 bg-blue-500 text-white rounded" id="closeModal">Close</button>
        </div>
    </div>

    <div id="loadingIndicator" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="spinner"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js">
    </script>
    <script src="{{ asset('calendar/js/plugin.js') }}"></script>

    @if (Session::has('success'))
        <script>
            Toastify({
                text: "{{ Session::get('success') }}",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(0deg, rgba(77,125,67,1) 0%, rgba(0,170,119,1) 100%)",
                },
                onClick: function() {} // Callback after click
            }).showToast();
        </script>
    @endif

    @if (Session::has('deleted'))
        <script>
            Toastify({
                text: "{{ Session::get('deleted') }}",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(0deg, rgba(162,66,126,1) 0%, rgba(208,29,135,1) 100%)",
                },
                onClick: function() {} // Callback after click
            }).showToast();
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var headerConfig = {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            };

            if (window.innerWidth <= 768) {
                headerConfig = {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                };
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                events: '{{ route('events') }}',
                timeZone: 'Australia/Perth',
                height: 'auto',
                headerToolbar: headerConfig,
                selectable: true,
                firstDay: 1,
                slotMinTime: '06:00:00', // Set the minimum time to 6 AM
                slotMaxTime: '23:00:00',
                longPressDelay: 500,
                touchescreen: true,
                // title date format change if does not need remove it
                dayHeaderContent: function(arg) {
                    var date = arg.date;
                    var dayOfWeek = date.toLocaleString('en', {
                        weekday: 'short'
                    });
                    var dayOfMonth = date.getDate();
                    var month = date.toLocaleString('en', {
                        month: '2-digit'
                    });
                    return dayOfWeek + ' ' + dayOfMonth + '/' + month;
                },

                loading: function(isLoading) {
                    if (isLoading) {
                        loadingIndicator.classList.remove('hidden');
                    } else {
                        loadingIndicator.classList.add('hidden');
                    }
                },
                select: async function(start, end, allDay) {


                    var startDate = new Date(start.startStr);
                    var endDate = new Date(start.endStr);

                    // Extract date and time components
                    var startedDate = startDate.toISOString().split('T')[0];
                    var startedTime = startDate.toTimeString().split(' ')[0].slice(0,
                        5); // Extract HH:mm

                    var endedDate = endDate.toISOString().split('T')[0];
                    var endedTime = endDate.toTimeString().split(' ')[0].slice(0, 5); // Extract HH:mm

                    console.log(startedDate);
                    console.log(endedTime);

                    var starthourmin = '';
                    for (var hours = 6; hours < 22; hours++) {
                        for (var minutes = 0; minutes < 60; minutes += 15) {
                            var timeString =
                                (hours < 10 ? "0" : "") + hours + ":" +
                                (minutes === 0 ? "00" : minutes);

                            var isSelected = timeString === startedTime ? 'selected' :
                                ''; // Check if the option should be selected

                            starthourmin += '<option value="' + timeString + '" ' + isSelected + '>' +
                                timeString + '</option>';
                        }
                    }

                    var endhourmin = '';
                    for (var hours = 6; hours < 22; hours++) {
                        for (var minutes = 0; minutes < 60; minutes += 15) {
                            var timeString2 =
                                (hours < 10 ? "0" : "") + hours + ":" +
                                (minutes === 0 ? "00" : minutes);

                            var isSelected = timeString2 === endedTime ? 'selected' :
                                ''; // Check if the option should be selected
                            endhourmin += '<option value="' + timeString2 + '" ' + isSelected + '>' +
                                timeString2 + '</option>';
                        }
                    }

                    const {
                        value: formValues
                    } =
                    await Swal.fire({
                        title: 'Add Event',
                        html: '<input type="text" class="input-field2" id="swalEvtCustomerName" name="customer_name" placeholder="Add Title (Customer Name) (Required)" required>' +
                            // '<input type="text" class="input-field2" id="swalEvtResPerson" name="res_person" placeholder="Responsible Person (Optional)">' +
                            '<input type="text" class="input-field2" id="swalEvtPhone" name="phone" placeholder="Enter Phone No (Required)" required>' +
                            '<input type="text" class="input-field2" id="swalEvtAddress" name="address" placeholder="Enter Address. Ex:4 Toronto P (Required)" required>' +
                            '<input type="text" class="input-field2" id="swalEvtCity" name="city" placeholder="Enter City. Ex: Wanneroo (Required)" required>' +
                            '<input type="text" class="input-field2" id="swalEvtState" name="state" placeholder="Enter State. Ex: WA, 6065 (Required)" required>' +
                            '<select name="room_id" required id="swalEvtRoomId" class="input-field2"><option value="">Room No. (Required)</option>' +
                            @foreach ($rooms as $room)
                                '<option value="{{ $room->id }}">{{ $room->name }}</option>' +
                            @endforeach
                        '</select>' +
                        '<label>Start Date: </label><input type="date" name="swalEvtStartdatetimeCal" id="swalEvtStartdatetimeCal" onchange="handleStartDateTimeChange()" value="' +
                        startedDate +
                        '" placeholder="Start Time & Date" required /><select onchange="handleStartDateTimeChange()" name="swalEvtstarthourminCal" required id="swalEvtstarthourminCal" >' +
                        starthourmin + '</select>' +
                        '<br><label>End Date: </label><input type="date" name="swalEvtEnddatetimeCal" id="swalEvtEnddatetimeCal" value="' +
                        endedDate +
                        '"  required placeholder="End Time & Date" /><select name="swalEvtendhourminCal" required id="swalEvtendhourminCal" >' +
                        endhourmin +
                        '<input type="text" class="input-field2" id="swalEvtResDay" name="day" placeholder="Dayes (Required)">' +
                        '<textarea name="description" id="swalEvtDescription" class="input-field2 mb-2" placeholder="Enter Description (Note) (Required)" required></textarea>',
                        focusConfirm: false,
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        confirmButtonText: 'Submit',
                        preConfirm: () => {
                            return [
                                document.getElementById('swalEvtCustomerName').value,
                                // document.getElementById('swalEvtResPerson').value,
                                document.getElementById('swalEvtPhone').value,
                                document.getElementById('swalEvtAddress').value,
                                document.getElementById('swalEvtCity').value,
                                document.getElementById('swalEvtState').value,
                                document.getElementById('swalEvtRoomId').value,
                                document.getElementById('swalEvtStartdatetimeCal').value,
                                document.getElementById('swalEvtstarthourminCal').value,
                                document.getElementById('swalEvtEnddatetimeCal').value,
                                document.getElementById('swalEvtendhourminCal').value,
                                document.getElementById('swalEvtResDay').value,
                                document.getElementById('swalEvtDescription').value,
                            ]
                        }
                    });

                    if (formValues) {
                        // Add event
                        console.log('Form Values:', formValues); // Add this line
                        //  console.log('Summary:', document.getElementById('summmery').value);
                        fetch("{{ route('events.store') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    request_type: 'addEvent',
                                    start: start.startStr,
                                    end: start.endStr,
                                    event_data: formValues
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status == 1) {
                                    Swal.fire('Event added successfully!', '', 'success');
                                }
                                else {
                                    Swal.fire(data.message, '', 'error');
                                }

                                // Refetch events from all sources and rerender
                                calendar.refetchEvents();
                            })
                            .catch(console.error);
                    }
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    // change the border color
                    info.el.style.borderColor = 'red';
                    Swal.fire({
                        // title: info.event.customer_name,
                        //text: info.event.extendedProps.description,
                        icon: 'info',
                        html: `
                                <p>Customer Name : ${info.event.extendedProps.customer_name}</p>
                                <p>Phone : ${info.event.extendedProps.phone}</p>
                                <p>Address : ${info.event.extendedProps.address}</p>
                                <p>City : ${info.event.extendedProps.city}</p>
                                <p>State : ${info.event.extendedProps.state}</p>
                                <p>Room : ${info.event.extendedProps.room}</p>
                                <p>Start Time : ${info.event.extendedProps.cus_start}</p>
                                <p>End Time : ${info.event.extendedProps.cus_end}</p>
                                <p class="mb-3">Description : ${info.event.extendedProps.description}</p>
                                <a href="{{ route('calendar.invoices.index') }}/${info.event.extendedProps.event_id}" class="bg-blue-500 text-white px-4 py-2 mr-2 rounded-md">Payment</a>
                                <a href="{{ route('calendar.invoices.index') }}/${info.event.extendedProps.event_id}" class="bg-red-500 text-white px-4 py-2 mr-2 rounded-md">Due</a>
                            `,

                        showCloseButton: true,
                        showCancelButton: true,
                        showDenyButton: true,
                        //  responsive: false,
                        cancelButtonText: 'Close',
                        confirmButtonText: 'Delete',
                        denyButtonText: 'Edit',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Delete event
                            fetch("{{ url('events-delete') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        request_type: 'deleteEvent',
                                        calender_id: info.event.extendedProps
                                            .calender_id
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status == 1) {
                                        Swal.fire('Event deleted successfully!', '',
                                            'success');
                                        // Swal.fire('Event Delete feature is disabled for this demo!', '', 'warning');
                                    } else {
                                        Swal.fire(data.message, '', 'error');
                                    }

                                    // Refetch events from all sources and rerender
                                    calendar.refetchEvents();
                                })
                                .catch(console.error);
                        } else if (result.isDenied) {
                            info.jsEvent.preventDefault();
                            //    console.log(info.event.extendedProps.user_id);

                            var optionsHTML = '<option value="' + info.event.extendedProps
                                .user_id + '" selected ">' + info.event.extendedProps.created +
                                '</option>';
                            @foreach ($rooms as $v)
                                optionsHTML +=
                                    '<option value="{{ $v->id }}" style="background:{{ $v->color }};color:white" {{ $v->id == '+info.event.extendedProps.user_id+' ? `selected` : '' }}>{{ $v->name }}</option>';
                            @endforeach

                            var StartdateTimeString = new Date(info.event.extendedProps
                                .cus_start);
                            var enddateTimeString = new Date(info.event.extendedProps.cus_end);


                            var startedDate_edit = StartdateTimeString.toISOString().split('T')[
                                0];
                            var startedTime_edit = StartdateTimeString.toTimeString().split(
                                ' ')[0].slice(0, 5);


                            var endedDate_edit = enddateTimeString.toISOString().split('T')[0];
                            var endedTime_edit = enddateTimeString.toTimeString().split(' ')[0]
                                .slice(0, 5);

                            var starthourmin_edit = '';
                            for (var hours = 6; hours < 22; hours++) {
                                for (var minutes = 0; minutes < 60; minutes += 15) {
                                    var timeString =
                                        (hours < 10 ? "0" : "") + hours + ":" +
                                        (minutes === 0 ? "00" : minutes);

                                    var isSelected = timeString === startedTime_edit ?
                                        'selected' :
                                        ''; // Check if the option should be selected

                                    starthourmin_edit += '<option value="' + timeString + '" ' +
                                        isSelected + '>' + timeString + '</option>';
                                }
                            }

                            var endhourmin_edit = '';
                            for (var hours = 6; hours < 22; hours++) {
                                for (var minutes = 0; minutes < 60; minutes += 15) {
                                    var timeString2 =
                                        (hours < 10 ? "0" : "") + hours + ":" +
                                        (minutes === 0 ? "00" : minutes);

                                    var isSelected = timeString2 === endedTime_edit ?
                                        'selected' :
                                        ''; // Check if the option should be selected

                                    endhourmin_edit += '<option value="' + timeString2 + '" ' +
                                        isSelected + '>' + timeString2 + '</option>';
                                }
                            }

                            Swal.fire({
                                title: 'Edit Event',
                                html: '<input type="text" class="input-field2" id="swalEvtSummmery_edit" name="swalEvtSummmery_edit" placeholder="Add Title (To Whom) - Required" value="' +
                                    info.event.title + '" required>' +
                                    '<input type="text" class="input-field2" id="swalEvtLocation_edit" name="swalEvtLocation_edit"  value="' +
                                    info.event.extendedProps.location +
                                    '" placeholder="Location (Optional)" >' +
                                    '<input type="text" class="input-field2" id="swalEvtPhone_edit" name="swalEvtPhone_edit" placeholder="edit phone No"  value="' +
                                    info.event.extendedProps.phone + '" required>' +
                                    '<select name="swalEvtColorId_edit" required id="swalEvtColorId_edit" class="input-field2">' +
                                    optionsHTML +
                                    '</select>' +
                                    '<label>Start Date: </label><input type="date" name="swalEvtStartdatetime_edit" id="swalEvtStartdatetime_edit"  onchange="handleStartDateTimeChange3()" value="' +
                                    startedDate_edit +
                                    '" placeholder="Start Time & Date" required /><select onchange="handleStartDateTimeChange3()" name="swalEvtStarttime_edit" required id="swalEvtStarttime_edit" class=""  >' +
                                    starthourmin_edit + '</select>' +
                                    '<br><label>End Date: </label><input type="date" name="swalEvtEnddatetime_edit" id="swalEvtEnddatetime_edit" class=" " value="' +
                                    endedDate_edit +
                                    '"  required placeholder="End Time & Date" /><select name="swalEvtEndtime_edit" required id="swalEvtEndtime_edit"   class="">' +
                                    endhourmin_edit +
                                    '</select><select name="swalEvtrecurrencetype_edit" required id="swalEvtrecurrencetype_edit"   class="input-field2"><option selected value="none" ' +
                                    (info.event.extendedProps.recurrence_type ===
                                        'none' ? 'selected' : '') +
                                    ' >No Recurring</option><option  ' + (info.event
                                        .extendedProps.recurrence_type === 'daily' ?
                                        'selected' : '') +
                                    ' value="daily">Daily</option><option value="weekly" ' +
                                    (info.event.extendedProps.recurrence_type ===
                                        'weekly' ? 'selected' : '') +
                                    ' >Weekly</option><option value="monthly" ' + (info
                                        .event.extendedProps.recurrence_type ===
                                        'monthly' ? 'selected' : '') +
                                    '>Monthly</option><option value="yearly" ' + (info
                                        .event.extendedProps.recurrence_type ===
                                        'yearly' ? 'selected' : '') +
                                    '>Yearly</option></select>' +
                                    '<textarea name="swalEvtDescription_edit" id="swalEvtDescription_edit" class="input-field2"  placeholder="Enter Description"  required="">' +
                                    info.event.extendedProps.description +
                                    '</textarea>',
                                focusConfirm: false,
                                confirmButtonText: 'Submit',
                                showCancelButton: true,
                                cancelButtonText: 'Close',
                                preConfirm: () => {
                                    return [
                                        document.getElementById(
                                            'swalEvtSummmery_edit').value,
                                        document.getElementById(
                                            'swalEvtLocation_edit').value,
                                        document.getElementById(
                                            'swalEvtPhone_edit').value,
                                        document.getElementById(
                                            'swalEvtColorId_edit').value,
                                        document.getElementById(
                                            'swalEvtStartdatetime_edit').value,
                                        document.getElementById(
                                            'swalEvtStarttime_edit').value,
                                        document.getElementById(
                                            'swalEvtEnddatetime_edit').value,
                                        document.getElementById(
                                            'swalEvtEndtime_edit').value,
                                        document.getElementById(
                                            'swalEvtDescription_edit').value,
                                        document.getElementById(
                                            'swalEvtrecurrencetype_edit').value
                                    ];
                                }
                            }).then((result) => {
                                if (result.value) {
                                    // Event update request
                                    fetch("{{ url('events-update') }}", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                            },
                                            body: JSON.stringify({
                                                request_type: 'editEvent',
                                                start: info.event.startStr,
                                                end: info.event.endStr,
                                                event_id: info.event
                                                    .extendedProps
                                                    .calender_id,
                                                event_data: result.value
                                            }),
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status == 1) {
                                                Swal.fire(
                                                    'Event updated successfully!',
                                                    '', 'success');
                                                // Swal.fire('Event Update feature is disabled for this demo!', '', 'warning');
                                            } else {
                                                Swal.fire(data.message, '',
                                                    'error');
                                            }

                                            // Refetch events from all sources and rerender
                                            calendar.refetchEvents();
                                        })
                                        .catch(console.error);
                                }
                            });
                        } else {
                            Swal.close();
                        }
                    });
                }
            });

            calendar.render();

            document.getElementById('search-button').addEventListener('click', function() {
                var searchQuery = document.getElementById('search-input').value;
                // Build the URL with the search parameter
                var eventsUrl = '{{ url('events') }}' + '?search=' + searchQuery;
                // Remove existing event sources
                calendar.removeAllEventSources();
                // Send an AJAX request to fetch events
                $.ajax({
                    type: 'GET',
                    url: eventsUrl,
                    success: function(data) {
                        var searchResultEventSource = {
                            events: data,
                        };
                        calendar.addEventSource(searchResultEventSource);
                        var searchResultDate = new Date(data[0].cus_start);
                        calendar.gotoDate(searchResultDate);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('Error:', errorThrown);
                    }
                });
            });


            // document.getElementById('search-button').addEventListener('click', function() {
            //     var searchQuery =  document.getElementById('search-input').value;
            //     // Build the URL with the search parameter
            //     var eventsUrl = '{{ url('events') }}' + '?search=' + searchQuery;
            //     console.log(eventsUrl);
            //     calendar.removeAllEventSources(); // Remove existing event sources
            //     calendar.addEventSource(eventsUrl); // Add the modified URL as the new event source
            //     var searchResultDate = new Date('08/13/2023'); // Replace with your actual search result date
            //     // Change the FullCalendar view to the date of the search result
            //     calendar.gotoDate(searchResultDate);
            //  });


            // var ajaxInProgress = false; // Flag to track whether an AJAX request is in progress
            // // Handle form submission
            // document.getElementById('event-search-form').addEventListener('submittt', function(e) {
            //     e.preventDefault();
            //     var searchQuery = document.getElementById('search-input').value;

            //     // Check if an AJAX request is already in progress
            //     if (ajaxInProgress) {
            //         return; // Do nothing if a request is already running
            //     }

            //     // Send an AJAX request
            //     ajaxInProgress = true;

            //     $.ajax({
            //         type: 'GET',
            //         data: {
            //             searchData: searchQuery
            //         },
            //         url: "{{ url('events') }}",
            //         success: function(data) {
            //             // Handle the response data and update the calendar as needed

            //             // Clear existing event sources
            //             calendar.removeAllEventSources();

            //             // Create a new event source for the search results
            //             var searchResultEventSource = {
            //                 events: data,
            //                 // Additional event source options if needed
            //             };

            //             // Add the search result event source to FullCalendar
            //             calendar.addEventSource(searchResultEventSource);

            //             // Find the date of the search result (assuming it's in data)
            //             var searchResultDate = new Date('05/08/2023'); // Replace with your actual search result date

            //             // Change the FullCalendar view to the date of the search result
            //             calendar.gotoDate(searchResultDate);

            //             console.log(data);
            //         },
            //         error: function(xhr, textStatus, errorThrown) {
            //             console.error('Error:', errorThrown);
            //         },
            //         complete: function() {
            //             // Reset the flag when the request is complete, whether it succeeds or fails
            //             ajaxInProgress = false;
            //         }
            //     });
            // });


        });
    </script>

    <script>
        function handleStartDateTimeChange() {
            const startDateInput = document.getElementById('swalEvtStartdatetimeCal');
            const startTimeSelect = document.getElementById('swalEvtstarthourminCal');
            const endDateInput = document.getElementById('swalEvtEnddatetimeCal');
            const endHourMinSelect = document.getElementById('swalEvtendhourminCal');

            if (!startDateInput.value || !startTimeSelect.value) {
                return;
            }

            const [startYear, startMonth, startDay] = startDateInput.value.split('-');
            const [startHour, startMinute] = startTimeSelect.value.split(':');

            const startDateTime = new Date(startYear, startMonth - 1, startDay, startHour, startMinute);

            // Add 15 minutes to the start date and time
            startDateTime.setMinutes(startDateTime.getMinutes() + 15);

            const endYear = startDateTime.getFullYear();
            const endMonth = startDateTime.getMonth() + 1;
            const endDay = startDateTime.getDate();
            const endHour = startDateTime.getHours();
            const endMinute = startDateTime.getMinutes();

            const formattedEndDate = `${endYear}-${String(endMonth).padStart(2, '0')}-${String(endDay).padStart(2, '0')}`;
            const formattedEndTime = `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;

            endDateInput.value = formattedEndDate;
            endHourMinSelect.value = formattedEndTime;
        }

        function handleStartDateTimeChange2() {
            const startDateInput = document.getElementById('swalEvtStartdatetime');
            const startTimeSelect = document.getElementById('swalEvtstarthourmin');
            const endDateInput = document.getElementById('swalEvtEnddatetime');
            const endHourMinSelect = document.getElementById('swalEvtendhourmin');

            if (!startDateInput.value || !startTimeSelect.value) {
                return;
            }

            const [startYear, startMonth, startDay] = startDateInput.value.split('-');
            const [startHour, startMinute] = startTimeSelect.value.split(':');

            const startDateTime = new Date(startYear, startMonth - 1, startDay, startHour, startMinute);

            // Add 15 minutes to the start date and time
            startDateTime.setMinutes(startDateTime.getMinutes() + 15);

            const endYear = startDateTime.getFullYear();
            const endMonth = startDateTime.getMonth() + 1;
            const endDay = startDateTime.getDate();
            const endHour = startDateTime.getHours();
            const endMinute = startDateTime.getMinutes();

            const formattedEndDate = `${endYear}-${String(endMonth).padStart(2, '0')}-${String(endDay).padStart(2, '0')}`;
            const formattedEndTime = `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;

            endDateInput.value = formattedEndDate;
            endHourMinSelect.value = formattedEndTime;
        }



        function handleStartDateTimeChange3() {
            const startDateInput = document.getElementById('swalEvtStartdatetime_edit');
            const startTimeSelect = document.getElementById('swalEvtStarttime_edit');
            const endDateInput = document.getElementById('swalEvtEnddatetime_edit');
            const endHourMinSelect = document.getElementById('swalEvtEndtime_edit');

            if (!startDateInput.value || !startTimeSelect.value) {
                return;
            }

            const [startYear, startMonth, startDay] = startDateInput.value.split('-');
            const [startHour, startMinute] = startTimeSelect.value.split(':');

            const startDateTime = new Date(startYear, startMonth - 1, startDay, startHour, startMinute);

            // Add 15 minutes to the start date and time
            startDateTime.setMinutes(startDateTime.getMinutes() + 15);

            const endYear = startDateTime.getFullYear();
            const endMonth = startDateTime.getMonth() + 1;
            const endDay = startDateTime.getDate();
            const endHour = startDateTime.getHours();
            const endMinute = startDateTime.getMinutes();

            const formattedEndDate = `${endYear}-${String(endMonth).padStart(2, '0')}-${String(endDay).padStart(2, '0')}`;
            const formattedEndTime = `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;

            endDateInput.value = formattedEndDate;
            endHourMinSelect.value = formattedEndTime;
        }

        $(document).ready(function() {
            $(".date_picker").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
            });
        });

        function handler(e) {
            var startDatetimeInput = document.getElementById('swalEvtStartdatetime');
            var endDatetimeInput = document.getElementById('swalEvtEnddatetime');

            var startDatetime = new Date(startDatetimeInput.value);

            if (!isNaN(startDatetime)) {
                var startYear = startDatetime.getFullYear();
                var startMonth = startDatetime.getMonth() + 1; // JavaScript months are 0-based
                var startDay = startDatetime.getDate();
                var startHours = startDatetime.getHours();
                var startMinutes = startDatetime.getMinutes() + 30; // Adding 30 minutes

                // Adjust for crossing into the next hour
                if (startMinutes >= 60) {
                    startMinutes -= 60;
                    startHours += 1;
                }

                // Format month, day, hours, and minutes as two digits
                startMonth = ('0' + startMonth).slice(-2);
                startDay = ('0' + startDay).slice(-2);
                startHours = ('0' + startHours).slice(-2);
                startMinutes = ('0' + startMinutes).slice(-2);

                var endDatetimeFormatted = `${startYear}-${startMonth}-${startDay}T${startHours}:${startMinutes}`;
                endDatetimeInput.value = endDatetimeFormatted;
            }
        }


        function handler2(e) {
            var startDatetimeInput = document.getElementById('swalEvtStartdatetime_edit');
            var endDatetimeInput = document.getElementById('swalEvtEnddatetime_edit');
            var startDatetime = new Date(startDatetimeInput.value);

            if (!isNaN(startDatetime)) {
                var startYear = startDatetime.getFullYear();
                var startMonth = startDatetime.getMonth() + 1; // JavaScript months are 0-based
                var startDay = startDatetime.getDate();
                var startHours = startDatetime.getHours();
                var startMinutes = startDatetime.getMinutes() + 30; // Adding 30 minutes

                // Adjust for crossing into the next hour
                if (startMinutes >= 60) {
                    startMinutes -= 60;
                    startHours += 1;
                }

                // Format month, day, hours, and minutes as two digits
                startMonth = ('0' + startMonth).slice(-2);
                startDay = ('0' + startDay).slice(-2);
                startHours = ('0' + startHours).slice(-2);
                startMinutes = ('0' + startMinutes).slice(-2);

                var endDatetimeFormatted = `${startYear}-${startMonth}-${startDay}T${startHours}:${startMinutes}`;
                endDatetimeInput.value = endDatetimeFormatted;
            }
        }

        $('#startdatetime').on('change', function() {
            const originalDate = new Date(this.value);
            const newDate = new Date(originalDate.getTime() + 30 * 60000);
            const formattedDate = formatDate(newDate);
            $('#enddatetime').val(formattedDate);
        });

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        const inputFields = document.querySelectorAll('.input-field');

        inputFields.forEach(input => {
            input.addEventListener('input', function() {
                if (input.value) {
                    input.classList.add('has-content');
                } else {
                    input.classList.remove('has-content');
                }
            });
        });

        function userInfoUpdae(userid, full_name, email, color_name, color, calendar_id) {

            $('#userid').val(userid);
            $('#user_name').val(full_name).addClass('has-content');
            $('#user_email').val(email).addClass('has-content');
            $('#user_color_name').val(color_name).addClass('has-content');
            $('#user_color').val(color).addClass('has-content');
            $('#user_calendar_id').val(calendar_id).addClass('has-content');

            let modalID = 'user-modal-id';
            document.getElementById(modalID).classList.toggle("hidden");
            document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
            document.getElementById(modalID).classList.toggle("flex");
            document.getElementById(modalID + "-backdrop").classList.toggle("flex");
        }
    </script>


    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function(OneSignal) {
            OneSignal.init({
                appId: "f1e0e7dc-71c3-4cd9-91ad-c71ab053db53",
            });
        });
    </script>
</body>

</html>
