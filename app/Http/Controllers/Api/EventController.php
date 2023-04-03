<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use Validator, DB, Exception;

class EventController extends BaseController{
    public function index(Request $request){
        //Get event list
    }

    public function create(Request $request){
        //create event
    }

    public function store(Request $request){
        if (! Gate::allows('isAdminAuthorized')) {
            return $this->sendError('Unautorized Access.', [],403);
        }
 
        // Store the event data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image',
            'date_occurrence' => 'required|date_format:Y-m-d',
            'visibility' => 'nullable|in:0,1',
            'timeline_id' => 'required|exists:timelines,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }

        try {
            DB::beginTransaction();
            $statusCode = 200;
            
            $eventObj = new Event();
            $eventObj->title = $request->title;
            $eventObj->body = $request->body;
            $eventObj->date_occurrence = $request->date_occurrence;
            $eventObj->timeline_id = $request->timeline_id;
            $eventObj->visibility = $request->visibility;
            //Save image
            $fileName = '';
            if($request->image && $request->image->isValid()){
                $fileName = $eventObj->title.'_'.time().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('images/events'),$fileName);
                $eventObj->image = $fileName;
            }
            
            if($eventObj->save()){
                $response['event'] =  [
                    'id' => $eventObj->id,
                    'title' => $eventObj->title,
                    'body' => $eventObj->body,
                    'image' => 'public/images/events/'.$eventObj->image,
                    'date_occurrence' => $eventObj->date_occurrence,
                    'visibility' => $eventObj->visibility,
                    'timeline_id' => $eventObj->timeline_id,
                ];
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollback();
            $statusCode = 500;
            $errorMessages = $e->getMessage();
            $error = 'There is some error while processing your request. Please try after some time.'; 
        }finally{
            if($statusCode != 200)
                return $this->sendError($error, $errorMessages, $statusCode);
            else
                return $this->sendResponse($response, 'Event added successfully.');
        }
    }

    public function edit($id)
    {
        if (! Gate::allows('isAdminAuthorized')) {
            return $this->sendError('Unautorized Access.', [],403);
        }
        $eventObj = Event::where('id',$id)->first(); 
        if($eventObj && !empty($eventObj) && $eventObj->count()){
            return $this->sendResponse($eventObj->toArray());
        }else{
            return $this->sendError('Invalid Record', [], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (! Gate::allows('isAdminAuthorized')) {
            return $this->sendError('Unautorized Access.', [],403);
        }
 
        // Store the event data
        $validator = Validator::make($request->all(), [
            'visibility' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }

        try {
            $statusCode = 200;
            $eventObj = Event::where('id',$id)->first(); 
            if($eventObj && !empty($eventObj) && $eventObj->count()){
                DB::beginTransaction();
                $eventObj->visibility = $request->visibility;
                if($eventObj->update()){
                    $response['event'] =  [
                        'id' => $eventObj->id,
                        'visibility' => $eventObj->visibility
                    ];
                    DB::commit();
                }
            }else{
                $statusCode = 404;
                $error = 'Invalid Record';
                $errorMessages = [];
            }
        } catch (Exception $e) {
            DB::rollback();
            $statusCode = 500;
            $errorMessages = $e->getMessage();
            $error = 'There is some error while processing your request. Please try after some time.'; 
        }finally{
            if($statusCode != 200)
                return $this->sendError($error, $errorMessages, $statusCode);
            else
                return $this->sendResponse($response, 'Event visibility updated successfully.');
        }
    }

    public function show($id)
    {
        $eventObj = Event::where('id',$id)->where('visibility','=', '1')->with('timeline')->first();
        if($eventObj && !empty($eventObj) && $eventObj->count()){
            $response['event'] =  [
                'id' => $eventObj->id,
                'title' => $eventObj->title,
                'body' => $eventObj->body,
                'image' => 'public/images/events/'.$eventObj->image,
                'date_occurrence' => $eventObj->date_occurrence,
                'visibility' => $eventObj->visibility,
                'timeline_id' => $eventObj->timeline_id,
            ];
            $response['timeline'] =  [
                'id' => $eventObj->timeline->id,
                'title' => $eventObj->timeline->title,
                'description' => $eventObj->timeline->description,
                'cover_image' => 'public/images/timelines/'.$eventObj->timeline->cover_image
            ];
            return $this->sendResponse($response);
        }else{
            return $this->sendError('Invalid Record', [], 404);
        }
    }

}