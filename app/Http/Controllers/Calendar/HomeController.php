<?php

namespace App\Http\Controllers\Calendar;

use App\Models\User;
use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use Spatie\GoogleCalendar\Event;
use App\Models\Calender;
use Carbon\Carbon;
use Exception;
use Session;
use Pagination;
use DateTime;
use Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();
        $datas['all_list']  = Calendar::whereBetween('startdatetime', [$start, $end])->orderBy('startdatetime', 'desc')->paginate(10);
        $datas['rooms'] = [
            101 => 'R-101',
            102 => 'R-102',
            103 => 'R-103',
            104 => 'R-104',
            105 => 'R-105',
            106 => 'R-106',
        ];

        // Apply the search filter if a search query is provided
        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $events = Calendar::where('summmery', 'like', '%' . $searchQuery . '%')->paginate(50);
            return response()->json($events);
        }
        return View('calendar.home', $datas);
    }

    public function getEvents(Request $request)
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));

        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $events =     Calendar::where(function ($query) use ($searchQuery) {
                $query->where('summmery', 'like', '%' . $searchQuery . '%')
                    ->orWhere('phone', 'like', '%' . $searchQuery . '%')
                    ->orWhere('startdatetime', 'like', '%' . $searchQuery . '%');
            })->get();
        } else {
            $events = Calendar::where(function ($query) use ($start, $end) {
                $query->where('startdatetime', '<=', $end)
                    ->where('enddatetime', '>=', $start);
            })->get();
        }



        $formattedEvents = [];

        foreach ($events as $event) {
            if ($event->recurrence_type != "none") {
                $occurrences = $this->calculateOccurrences($event, $start, $end);
                foreach ($occurrences as $occurrence) {
                    $formattedEvents[] = $this->formatEvent2($occurrence);
                }
            } else {
                $formattedEvents[] = $this->formatEvent($event);
            }
        }

        return response()->json($formattedEvents);
    }

    private function formatEvent($event)
    {
        $userInfo = User::where('id', $event->user_id)->select('name', 'id', 'color_code')->first();

        return [
            'calender_id' => $event->calender_id,
            'title' => $event->summmery,
            'start' => $event->startdatetime,
            'end' => $event->enddatetime,
            'cus_start' => $event->startdatetime,
            'cus_end' => $event->enddatetime,
            'description' => $event->description,
            'phone' => $event->phone,
            'location' => $event->location,
            'color' => $userInfo->color_code,
            'created' => $userInfo->name,
            'user_id' => $event->user_id,
            'recurrence_type' => $event->recurrence_type,
        ];
    }

    private function formatEvent2($event)
    {
        $userInfo = User::where('id', $event['user_id'])->select('name', 'id', 'color_code')->first();

        return [
            'calender_id' => $event['calender_id'],
            'title' => $event['summmery'],
            'start' => $event['startdatetime'],
            'end' => $event['enddatetime'],
            'cus_start' => $event['startdatetime'],
            'cus_end' => $event['enddatetime'],
            'description' => $event['description'],
            'phone' => $event['phone'],
            'location' => $event['location'],
            'color' => $userInfo->color_code,
            'created' => $userInfo->name,
            'user_id' => $event['user_id'],
            'recurrence_type' => $event['recurrence_type'],
        ];
    }

    private function calculateOccurrences($baseEvent, $start, $end)
    {
        $occurrences = [];
        $currentDate = Carbon::parse($baseEvent->startdatetime);

        if ($baseEvent->recurrence_type != "none") {
            while ($currentDate <= $end) {

                $startdatetime = $currentDate;
                $datetime_object = new DateTime($startdatetime);
                $start_time = $datetime_object->format('H:i:s');
                $date_only = $datetime_object->format('Y-m-d');

                $enddatetime = $baseEvent->enddatetime;
                $edatetime_object = new DateTime($enddatetime);
                $end_time = $edatetime_object->format('H:i:s');

                $datetime_object = new DateTime($date_only . ' ' . $end_time);
                $enddatetimee = $datetime_object->format('Y-m-d H:i:s');

                $baseEventArray = $baseEvent->toArray();
                $baseEventArray['startdatetime'] = $currentDate->format('Y-m-d H:i:s');
                $baseEventArray['enddatetime'] = $enddatetimee;
                $occurrences[] = $baseEventArray;

                if ($baseEvent->recurrence_type === 'daily') {
                    $currentDate->addDay();
                } elseif ($baseEvent->recurrence_type === 'weekly') {
                    $currentDate->addWeek();
                } elseif ($baseEvent->recurrence_type === 'monthly') {
                    $currentDate->addMonth();
                } elseif ($baseEvent->recurrence_type === 'yearly') {
                    $currentDate->addYear();
                }
            }
        }

        return $occurrences;
    }


    public function getEvents_back(Request $request)
    {

        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));

        $events = Calendar::whereBetween('startdatetime', [$start, $end])->get();

        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $events = Calendar::where('summmery', 'like', '%' . $searchQuery . '%')
                ->orWhere('phone', 'like', '%' . $searchQuery . '%')
                ->orWhere('startdatetime', 'like', '%' . $searchQuery . '%')
                ->get();
        }

        $formattedEvents = [];
        foreach ($events as $event) {
            $userInfo = User::where('id', $event->user_id)->select('name', 'id', 'color_code')->first();
            $formattedEvents[] = [
                'calender_id' => $event->calender_id,
                'title' => $event->summmery,
                'start' => $event->startdatetime,
                'end' => $event->enddatetime,
                'cus_start' => $event->startdatetime,
                'cus_end' => $event->enddatetime,
                'description' => $event->description,
                'phone' => $event->phone,
                'location' => $event->location,
                'color' => $userInfo->color_code,
                'created' => $userInfo->name,
                'user_id' => $event->user_id,
            ];
        }

        return response()->json($formattedEvents);
    }



    public function sync_calender()
    {
        $data = [];
        // $start = Carbon::now()->startOfYear();
        // $end = Carbon::now()->endOfYear();

        $start =  Carbon::now()->subMonth()->startOfMonth();;
        $end = Carbon::now()->addMonth()->endOfMonth();

        // $calendarUsers = [
        //     'sumonta121@gmail.com',
        //     'aaaaaaaaaaaa@group.calendar.google.com',
        // ];

        $calendarUsers = User::select('id', 'calendar_id')->orderBy('id', 'asc')->get();

        // $calendarUsers = [
        //     'focustaxationwa@gmail.com',
        //     'focustaxation.anwar@gmail.com',
        //     'focustaxation.receptionist@gmail.com',
        //     'focustaxation.fateha@gmail.com',
        //     'focustaxation.fateha@gmail.com',
        // ];

        foreach ($calendarUsers as $calendarUser) {


            try {
                $events = Event::get($start, $end, ['calendarId' => $calendarUser->calendar_id]);
            } catch (\Exception $e) {
                // Handle exceptions raised during API request (e.g., network issues)
                // Log the error
                \Log::error('Error fetching events: ' . $e->getMessage());
                $hasErrors = true;  // Set the flag to indicate an error
                continue;
            }

            if (empty($events)) {
                // Handle the case where the calendar doesn't have any events
                // Log a message
                \Log::info('No events found for user ' . $calendarUser->id);
                continue;
            }



            foreach ($events as $row) {
                if (isset($row->error) && $row->error->reason === 'notFound') {
                    // Handle the case where an event doesn't exist in the Google Calendar
                    // Log a message
                    \Log::info('Event not found: ' . $row->error->message);
                    continue;
                }

                $check = Calendar::where('calender_id', $row->id)->first();
                if (empty($check)) {

                    $pattern = '/"(\+[\d\s\-()]+)"/';
                    if (preg_match($pattern, $row->description, $matches)) {
                        $phoneNumber = $matches[1];
                    } else {
                        $phoneNumber = '';
                    }

                    Calendar::create([
                        'user_id' => $calendarUser->id,  //  $row->creator->email??'null',
                        'calender_id' => $row->id,
                        'summmery' => $row->name ?? '',
                        'phone' => $phoneNumber ?? '',
                        'location' => $row->location ?? '8A Rochford way Girrawheen WA 6064',
                        'colorId' => $row->colorId ?? 1,
                        'description' => $row->description ?? 'Blank Description',
                        'startdatetime' => $row->startDateTime ?? now(),
                        'enddatetime' => $row->endDateTime ?? now(),
                        'recurrence_type' => 'none',
                        'status' => 0,
                    ]);
                }
            }
        }

        if (!$hasErrors) {
            // If there are no errors, redirect after the loop has completed
            Session::flash('success', 'Synchronize successful....');
            return redirect()->back();
        } else {
            // If there are errors, you can handle them or display an error message as needed
            Session::flash('error', 'There were errors during synchronization.');
            return redirect()->back();
        }

        return redirect()->back();

        // $datas['all_list']  = Calendar::whereBetween('startdatetime',[$start,$end])->orderBy('startdatetime','desc')->paginate(10);
        // return View('home',$datas);
    }



    public function store(Request $request)
    {

        $event = new Event;
        $validator = Validator::make($request->all(), [
            'summmery' => 'required',
            'description' => 'required',
            'startdatetime' => 'required',
            'enddatetime' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('success', $validator->errors());
            return redirect()->back();
            // return $this->sendError('Validation Error.', $validator->errors(), 422);
        }


        $inputDateTime = $request->startdatetime . ' ' . $request->swalEvtstarthourmin;
        $dateTime = new DateTime($inputDateTime);
        $startDateTime = $dateTime->format('Y-m-d H:i:s');

        $inputDateTime2 = $request->enddatetime . ' ' . $request->swalEvtendhourmin;
        $dateTime2      = new DateTime($inputDateTime2);
        $endDateTime    = $dateTime2->format('Y-m-d H:i:s');


        $userInfo = User::find($request->colorId);


        if (empty($request->location)) {
            $location = '8A Rochford way Girrawheen WA 6064';
        } else {
            $location = $request->location;
        }


        Calendar::create([
            'user_id' => $userInfo->id,
            'calender_id' => Str::random(10),
            'summmery' => $request->summmery,
            'location' => $location,
            'phone' => $request->phone ?? 1,
            'colorId' => $request->colorId,
            'description' => $request->description,
            'recurrence_type' => $request->recurrence_type,
            'startdatetime' => $startDateTime,
            'enddatetime' => $endDateTime,
            'status' => 0,
        ]);
        Session::flash('success', 'Event created successful....');
        return redirect()->back();
    }


    public function event_store(Request $request)
    {

        if (isset($request->event_data) && is_array($request->event_data)) {
            $value1 = isset($request->event_data[0]) ? $request->event_data[0] : null;
            $value2 = isset($request->event_data[2]) ? $request->event_data[2] : 1;
            $value3 = isset($request->event_data[3]) ? $request->event_data[3] : null;
            $value4 = isset($request->event_data[6]) ? $request->event_data[6] : null;

            if (empty($value1)) {
                // At least one of the values is empty
                return response()->json(['message' => 'To Whom field is required'], 400);
            }

            if (empty($value2)) {
                // At least one of the values is empty
                return response()->json(['message' => 'Phone number field is required'], 400);
            }

            if (empty($value3)) {
                // At least one of the values is empty
                return response()->json(['message' => 'With whom field is required'], 400);
            }
        } else {
            $response = array('status' => 0, 'message' => 'Data is missing');
            return response()->json($response);
        }

        $inputDateTime = $request->event_data[4] . ' ' . $request->event_data[5];
        $dateTime = new DateTime($inputDateTime);
        $startDateTime = $dateTime->format('Y-m-d H:i:s');

        $inputDateTime2 = $request->event_data[6] . ' ' . $request->event_data[7];
        $dateTime2      = new DateTime($inputDateTime2);
        $endDateTime    = $dateTime2->format('Y-m-d H:i:s');

        $userInfo = User::find($request->event_data[3]);

        if (empty($request->event_data[1])) {
            $location = '8A Rochford way Girrawheen WA 6064';
        } else {
            $location = $request->event_data[1];
        }

        // $event = new Event;
        // $inputDateTime       = $request->event_data[4];
        // $timestamp           = strtotime($inputDateTime);
        // $formattedDateTime   = date('Y-m-d H:i:s', $timestamp);


        // $event->name = $request->event_data[0];
        // $event->description =  $request->event_data[8] .' Phone No: "' . $request->event_data[2]. ' " ';
        // $event->colorId = $userInfo->color_id;
        // $event->location =  $location;
        // $event->startDateTime = Carbon::createFromFormat('Y-m-d H:i:s',$startDateTime);
        // $event->endDateTime = Carbon::createFromFormat('Y-m-d H:i:s',$endDateTime);

        // $new = $event->save();

        Calendar::create([
            'user_id' => $userInfo->id,
            'calender_id' => Str::random(10), //$new->id,
            'summmery' => $request->event_data[0],
            'location' => $location,
            'phone' => $request->event_data[2] ?? 1,
            'colorId' => $userInfo->color_code,
            'description' => $request->event_data[8],
            'recurrence_type' => $request->event_data[9],
            'startdatetime' => $startDateTime,
            'enddatetime' => $endDateTime,
            'status' => 0,
        ]);

        $response = array('status' => 1, 'message' => 'Success post data');
        return response()->json($response);
    }

    public function update(Request $request, $id = null)
    {

        try {
            $event = Event::find($id);
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'start' => 'required|date',
                'end' => 'required|date',

            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }


            $event->name = $request->name;
            $event->description =  $request->description;
            $event->startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $request->start);
            $event->endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $request->end);

            $new = $event->save();

            return $this->sendResponse($new->id, 'Success update data');
        } catch (Exception $e) {
            return $this->sendError('Not Found.', 'Google Calendar Not Found', 404);
        }
    }



    public function find($id = null)
    {
        try {
            //  $event = Event::find($id);
            // $data = ['id'=>$event->id,'name'=>$event->name,'startDate'=>$event->startDateTime,'endDate'=>$event->endDateTime,'description'=>$event->description];
            // return $this->sendResponse($data, 'Success get data');
            $datas['all_list']  = Calendar::whereBetween('startdatetime', [$start, $end])->orderBy('startdatetime', 'desc')->paginate(10);
            $datas['result']    = Calendar::where('calender_id', $id)->first();
            return View('home', $datas);
        } catch (Exception $e) {
            return $this->sendError('Not Found.', 'Google Calendar Not Found', 404);
        }
    }



    public function delete($id = null)
    {
        try {
            $event = Event::find($id);
            $event->delete();

            Calendar::where('calender_id', $id)->delete();
            Session::flash('deleted', 'This is deleted!');
            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('not_deleted', 'This is not_deleted!');
            return redirect()->back();
        }
    }




    public function event_update(Request $request)
    {

        if (isset($request->event_data) && is_array($request->event_data)) {
            $value1 = isset($request->event_data[0]) ? $request->event_data[0] : null;
            $value2 = isset($request->event_data[2]) ? $request->event_data[2] : 1;
            $value3 = isset($request->event_data[3]) ? $request->event_data[3] : null;
            $value4 = isset($request->event_data[6]) ? $request->event_data[6] : null;

            if (empty($value1)) {
                // At least one of the values is empty
                return response()->json(['message' => 'To Whom field is required'], 400);
            }

            if (empty($value2)) {
                // At least one of the values is empty
                return response()->json(['message' => 'Phone number field is required'], 400);
            }

            if (empty($value3)) {
                // At least one of the values is empty
                return response()->json(['message' => 'With whom field is required'], 400);
            }
        } else {
            $response = array('status' => 0, 'message' => 'Data is missing');
            return response()->json($response);
        }

        $inputDateTime = $request->event_data[4] . ' ' . $request->event_data[5];
        $dateTime = new DateTime($inputDateTime);
        $startDateTime = $dateTime->format('Y-m-d H:i:s');

        $inputDateTime2 = $request->event_data[6] . ' ' . $request->event_data[7];
        $dateTime2      = new DateTime($inputDateTime2);
        $endDateTime    = $dateTime2->format('Y-m-d H:i:s');


        $userInfo = User::find($request->event_data[3]);
        if (empty($request->event_data[1])) {
            $location = '8A Rochford way Girrawheen WA 6064';
        } else {
            $location = $request->event_data[1];
        }

        // $event = new Event;
        // $inputDateTime       = $request->event_data[4];
        // $timestamp           = strtotime($inputDateTime);
        // $formattedDateTime   = date('Y-m-d H:i:s', $timestamp);

        // $event = Event::find($request->event_id);

        // $inputDateTime = $request->event_data[4];
        // $timestamp = strtotime($inputDateTime);
        // $formattedDateTime = date('Y-m-d H:i:s', $timestamp);


        // $event->name = $request->event_data[0];
        // $event->description =  $request->event_data[6] .' Phone No: "' . $request->event_data[2]. ' " ';
        // $event->colorId =  $userInfo->color_id;
        // $event->location =  $location;
        // $event->startDateTime = Carbon::createFromFormat('Y-m-d H:i:s',$startDateTime);
        // $event->endDateTime = Carbon::createFromFormat('Y-m-d H:i:s',$endDateTime);
        // $new = $event->save();

        Calendar::where('calender_id', $request->event_id)->update([
            'user_id' => $userInfo->id,
            'summmery' => $request->event_data[0],
            'location' => $location,
            'phone' => $request->event_data[2] ?? 1,
            'colorId' => $userInfo->color_code,
            'description' => $request->event_data[8],
            'startdatetime' => $startDateTime,
            'enddatetime' => $endDateTime,
            'status' => 0,
        ]);

        $response = array('status' => 1, 'message' => 'Event Successfully updated');
        return response()->json($response);
    }



    public function user_update(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|min:6', // Password is optional and must be at least 6 characters if provided
            ]);


            if (!empty($validatedData['password'])) {
                // Hash the new password
                $hashedPassword = Hash::make($validatedData['password']);

                User::where('id', $request->userid)->update([
                    "name" => $request->name,
                    "email" => $request->email,
                    "color" => $request->color_name,
                    "color_code" => $request->color_code,
                    "calendar_id" => $request->calendar_id,
                    'password' => $hashedPassword, // Update the password field
                ]);
            } else {
                User::where('id', $request->userid)->update([
                    "name" => $request->name,
                    "email" => $request->email,
                    "color" => $request->color_name,
                    "color_code" => $request->color_code,
                    "calendar_id" => $request->calendar_id,
                ]);
            }


            Session::flash('success', $request->name . 'User info updated successfully');
            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('not_deleted', 'Something went wrong!');
            return redirect()->back();
        }
    }




    public function destroy(Request $request)
    {

        try {
            //   $event = Event::find($request->calender_id);
            //  $event->delete();

            Calendar::where('calender_id', $request->calender_id)->delete();

            $response = array('status' => 1, 'message' => 'Event deleted..');
            return response()->json($response);
        } catch (Exception $e) {
            $response = array('status' => 0, 'message' => 'Event not deleted..');
            return response()->json($response);
        }
    }
}
