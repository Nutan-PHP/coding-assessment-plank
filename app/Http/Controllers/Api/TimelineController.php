<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Gate;
use App\Models\Timeline;
use Validator, DB, Exception;

class TimelineController extends BaseController{
    public function index(Request $request){
        //Get timeline list
    }

    public function create(Request $request){
        //create timeline
    }

    public function store(Request $request){
        if (! Gate::allows('isAdminAuthorized')) {
            return $this->sendError('Unautorized Access.', [],403);
        }
 
        // Store the timeline data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'cover_image' => 'nullable|image'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }

        try {
            DB::beginTransaction();
            $statusCode = 200;
            
            $timelineObj = new Timeline();
            $timelineObj->title = $request->title;
            $timelineObj->description = $request->description;
            //Save image
            $fileName = '';
            if($request->cover_image && $request->cover_image->isValid()){
                $fileName = $timelineObj->title.'_'.time().'.'.$request->cover_image->getClientOriginalExtension();
                $request->cover_image->move(public_path('images/timelines'),$fileName);
                $timelineObj->cover_image = $fileName;
            }
            
            if($timelineObj->save()){
                $response['timeline'] =  [
                    'id' => $timelineObj->id,
                    'title' => $timelineObj->title,
                    'description' => $timelineObj->description,
                    'cover_image' => 'public/images/timelines/'.$timelineObj->cover_image
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
                return $this->sendResponse($response, 'Timeline added successfully.');
        }
    }

    public function edit($id)
    {
        //Edit timeline
    }

    public function update(Request $request, $id)
    {
        //Update timeline
    }

    public function show($id)
    {
        $timelineObj = Timeline::where('id',$id)->with('events', function($q){
            $q->where('visibility','=', '1')->orderby('date_occurrence','ASC');
        })->first();
        if($timelineObj && !empty($timelineObj) && $timelineObj->count()){
            $events = [];
            if(isset($timelineObj->events) && $timelineObj->events->count()){
                foreach($timelineObj->events as $eventObj){
                    $events[] = [
                        'id' => $eventObj->id,
                        'title' => $eventObj->title,
                        'body' => $eventObj->body,
                        'image' => 'public/images/events/'.$eventObj->image,
                        'date_occurrence' => $eventObj->date_occurrence,
                        'visibility' => $eventObj->visibility,
                        'timeline_id' => $eventObj->timeline_id,
                    ];
                }
            }
            
            $response['timeline'] =  [
                'id' => $timelineObj->id,
                'title' => $timelineObj->title,
                'description' => $timelineObj->description,
                'cover_image' => 'public/images/timelines/'.$timelineObj->cover_image
            ];
            $response['events'] = $events;
            return $this->sendResponse($response);
        }else{
            return $this->sendError('Invalid Record', [], 404);
        }
    }

}