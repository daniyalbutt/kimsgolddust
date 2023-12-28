<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Image;
use File;
use DB;

class ScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $schedule = Schedule::where('date', 'LIKE', "%$keyword%")
                ->orWhere('location', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $schedule = Schedule::paginate($perPage);
            }

            return view('schedule.schedule.index', compact('schedule'));
        }
        return response(view('403'), 403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('schedule.schedule.create');
        }
        return response(view('403'), 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            

            $schedule = new Schedule($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $schedule_path = 'uploads/schedules/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($schedule_path) . DIRECTORY_SEPARATOR. $profileImage);

                $schedule->image = $schedule_path.$profileImage;
            }
            $counter = DB::table('schedules')->count();
            $schedule->order_id = $counter + 1;
            $schedule->save();
            return redirect()->back()->with('message', 'Schedule added!');
        }
        return response(view('403'), 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $schedule = Schedule::findOrFail($id);
            return view('schedule.schedule.show', compact('schedule'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $schedule = Schedule::findOrFail($id);
            return view('schedule.schedule.edit', compact('schedule'));
        }
        return response(view('403'), 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $schedule = Schedule::where('id', $id)->first();
            $image_path = public_path($schedule->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/schedules/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/schedules/'.$fileNameToStore;               
        }


            $schedule = Schedule::findOrFail($id);
            $schedule->update($requestData);
            return redirect()->back()->with('message', 'Schedule updated!');
        }
        return response(view('403'), 403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('schedule','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Schedule::destroy($id);
            return redirect()->back()->with('message', 'Schedule deleted!');
        }
        return response(view('403'), 403);

    }
    
    public function scheduleOrder(Request $request){
        $new_data = $request->new_data;
        $old_data = $request->old_data;
        $new_schedule = Schedule::where('order_id', $new_data)->first();
        $old_schedule = Schedule::where('order_id', $old_data)->first();
        $new_schedule->order_id = $old_data;
        $old_schedule->order_id = $new_data;
        $new_schedule->save();
        $old_schedule->save();
    }
}
