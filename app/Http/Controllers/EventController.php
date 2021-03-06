<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Event;
use App\Events\NewAgendaEvent;
use Auth;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentEventRegister;
use App\Mail\AcceptEvent;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Presents overview of all Events
     * @param  Request $request
     */
    public function index(Request $request) {
      $aEventsAccepted = Event::all()->where('is_accepted', true);
      $aEventsOpen = Event::all()->where('is_accepted', false);
      return view('event/index', [
        "aEventsAccepted" => $aEventsAccepted, "aEventsOpen" => $aEventsOpen
      ]);
    }

    /**
     * Presents the form to add a event
     * @param  Request $request
     */
    public function createPage(Request $request) {
      return view("event/create");
    }

    public function ajaxCreate(Request $request) {
      $oValidator = Validator::make($request->all(), [
        'event_name' => 'required|max:255',
        'event_description' => 'required',
        'event_max_students' => 'nullable|integer|min:0',
        'event_start_date_time' => 'required|date_format:Y/m/d H:i',
        'event_end_date_time' => 'required|date_format:Y/m/d H:i|after:event_start_date_time',
        'event_straat' => 'required|max:255',
        'event_city' => 'required|max:255',
        'event_house_number' => 'required|integer',
        'event_house_number_addition' => 'nullable|max:1',
        'event_zipcode' => 'required|max:6|min:6|regex:/^\d{4}[a-z]{2}$/i',
      ]);

      if ($oValidator->fails()) {
        return response()->json([
          'status' => false,
          'errors' => $oValidator->errors()->all(),
        ]);
      }

      $oEvent = new Event();

      $oEvent->name = $request->event_name;
      $oEvent->description = $request->event_description;
      $oEvent->points = 0;
      $oEvent->max_students = $request->event_max_students;
      $oEvent->street = $request->event_straat;
      $oEvent->city = $request->event_city;
      $oEvent->house_number = $request->event_house_number;
      $oEvent->house_number_addition = $request->event_house_number_addition;
      $oEvent->zipcode = $request->event_zipcode;
      $oEvent->event_start_date_time = $request->event_start_date_time;
      $oEvent->event_end_date_time = $request->event_end_date_time;
      $oEvent->user_id = Auth::user()->id;
      $oEvent->is_accepted = false;

      $oEvent->save();

      if(Auth::user()->role == 'admin'){
          $oEvent->is_accepted = true;
          $oEvent->save();
          event(new NewAgendaEvent(eventToAgendaItem($oEvent)));
      }

      return response()->json([
        'status' => true,
      ]);
    }

    /**
     * Handles the post request to add a event
     * @param  Request $request
     */
    public function create(Request $request) {
      $request->validate([
        'event_name' => 'required|max:255',
        'event_description' => 'required',
        'event_points' => 'required|integer',
        'event_max_students' => 'nullable|integer|min:0',
        'event_start_date_time' => 'required|date_format:Y/m/d H:i',
        'event_end_date_time' => 'required|date_format:Y/m/d H:i|after:event_start_date_time',
        'event_straat' => 'required|max:255',
        'event_city' => 'required|max:255',
        'event_house_number' => 'required|integer',
        'event_house_number_addition' => 'nullable|max:1',
        'event_zipcode' => 'required|max:6|min:6|regex:/^\d{4}[a-z]{2}$/i',
      ]);

      $oEvent = new Event();

      $oEvent->name = $request->event_name;
      $oEvent->description = $request->event_description;
      $oEvent->points = $request->event_points;
      $oEvent->max_students = $request->event_max_students;
      $oEvent->street = $request->event_straat;
      $oEvent->city = $request->event_city;
      $oEvent->house_number = $request->event_house_number;
      $oEvent->house_number_addition = $request->event_house_number_addition;
      $oEvent->zipcode = $request->event_zipcode;
      $oEvent->event_start_date_time = $request->event_start_date_time;
      $oEvent->event_end_date_time = $request->event_end_date_time;
      $oEvent->user_id = Auth::user()->id;
      $oEvent->is_accepted = false;

      $oEvent->save();

      if(Auth::user()->role == 'admin'){
          $oEvent->is_accepted = true;
          $oEvent->save();
          event(new NewAgendaEvent(eventToAgendaItem($oEvent)));
      }

      return redirect()->route('event.index');
    }

    public function editPage(Request $request, $iId) {
      $oEvent = Event::find($iId);

      if (is_null($oEvent)) {
        return redirect()->route('event.index');
      }

      return view('event/edit', [
        'oEvent' => $oEvent
      ]);
    }

    public function edit(Request $request, $iId) {
      $request->validate([
        'event_name' => 'required|max:255',
        'event_description' => 'required',
        'event_points' => 'required|integer',
        'event_max_students' => 'nullable|integer|min:0',
        'event_start_date_time' => 'required|date_format:Y/m/d H:i',
        'event_end_date_time' => 'required|date_format:Y/m/d H:i|after:event_start_date_time',
        'event_straat' => 'required|max:255',
        'event_city' => 'required|max:255',
        'event_house_number' => 'required|integer',
        'event_house_number_addition' => 'nullable|max:1',
        'event_zipcode' => 'required|max:6|min:6|regex:/^\d{4}[a-z]{2}$/i',
      ]);

      $oEvent = Event::find($iId);
      if (is_null($oEvent)) {
        return redirect()->route('event.index');
      }

      $oEvent->name = $request->event_name;
      $oEvent->description = $request->event_description;
      $oEvent->points = $request->event_points;
      $oEvent->max_students = $request->event_max_students;
      $oEvent->street = $request->event_straat;
      $oEvent->city = $request->event_city;
      $oEvent->house_number = $request->event_house_number;
      $oEvent->house_number_addition = $request->event_house_number_addition;
      $oEvent->zipcode = $request->event_zipcode;
      $oEvent->event_start_date_time = $request->event_start_date_time;
      $oEvent->event_end_date_time = $request->event_end_date_time;

      $oEvent->save();

      return redirect()->route('event.index');
    }

    /**
     * Presents the agenda
     * @param  Request $request
     */
    public function agenda(Request $request) {
      $aEvents = Event::all()->where('is_accepted', true);
      $aEvents = eventToAgendaItems($aEvents);
      $sEvents = json_encode($aEvents);
      return view('event/agenda', [
        'sEvents' => $sEvents,
      ]);
    }

    /**
     * Finds a agenda item based on it and returns the agenda in json
     * @param  Request $request
     * @param  int  $iId        The id of the event
     * @return string           the json of the event
     */
    public function agendaDetails(Request $request, $iId) {
      $oEvent = Event::find($iId);
      return response()->json($oEvent);
    }

    public function delete(Request $request, $iId) {
        $oEvent = Event::find($iId);

        if (!is_null($oEvent)) {
          $oEvent->delete();
        }

        return redirect()->route('event.index');
    }

    public function accept(Request $request, $iId){
        $oEvent = Event::find($iId);

        if (is_null($oEvent)) {
          return redirect()->back();
        }

        $oEvent->is_accepted = true;
        $oEvent->save();
        Mail::to($oEvent->organiser->email)->send(new AcceptEvent($oEvent));
        event(new NewAgendaEvent(eventToAgendaItem($oEvent)));
        return redirect()->route('event.index');
    }

    public function details(Request $request, $iId){

        $oEvent = Event::find($iId);
        if (is_null($oEvent)) {
            return redirect()->route('event.index');
        }else{
          if (($oEvent->is_accepted == 0 && Auth::user()->role == 'admin') || $oEvent->is_accepted == 1) {
            return view('event.details', ['oEvent' => $oEvent]);
          }
          return redirect()->route('event.index');
        }
    }

    public function detailsGuest(Request $request, $iId)
    {
      $oEvent = Event::find($iId);
      if (is_null($oEvent)) {
        return redirect()->route('event.index');
      } else {
        return view('event/detailsguest', ['oEvent' => $oEvent]);
      }
    }

    public function studentRegisterPage(Request $request, $iId) {
      $oEvent = Event::find($iId);

      if (is_null($oEvent)) {
        return redirect()->route('event.agenda');
      }

      return view('event/student/register', [
        'oEvent' => $oEvent
      ]);
    }

    public function studentRegister(Request $request, $iId) {
      $oEvent = Event::find($iId);

      if (is_null($oEvent)) {
        return redirect()->route('event.agenda');
      }
      $oUser = Auth::user();
      if (!$oEvent->students->contains($oUser)) {
        if (is_null($oEvent->max_students) || $oEvent->max_students > $oEvent->students->count() || $oEvent->max_students === 0) {
          $oEvent->students()->save($oUser);
          Mail::to($oUser->email)->send(new StudentEventRegister($oEvent, $oUser));
          Session::flash('message', 'U bent toegevoegd aan het event');
        }
        else {
          Session::flash('message', 'Het maximum van het aantal studenten voor dit evenement is bereikt.');
        }
      }
      else {
        Session::flash('message', 'U bent al toegevoegd aan het event');
      }

      return redirect()->route('event.agenda');
    }

    public function presentPage(Request $request, $iId) {
      $oEvent = Event::find($iId);

      if (is_null($oEvent)) {
        return redirect()->route('event.index');
      }

      return view('event/present', [
        'oEvent' => $oEvent
      ]);
    }

    public function present(Request $request, $iId) {
      $request->validate([
        "present_user" => "exists:users,id",
      ]);
      $oEvent = Event::find($iId);

      if (is_null($oEvent)) {
        return redirect()->back();
      }

      foreach($oEvent->students as $oUser) {
        $oEvent->students()->updateExistingPivot($oUser, [
          'was_present' => false,
        ]);
        if (is_array($request->present_user) && in_array($oUser->id, $request->present_user)) {
          $oEvent->students()->updateExistingPivot($oUser, [
            'was_present' => true,
          ]);
        }
      }
      return redirect()->back();
    }
}
