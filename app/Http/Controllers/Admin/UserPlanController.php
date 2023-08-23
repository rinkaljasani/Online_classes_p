<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserPlanRequest;
use App\Models\Plan;
use App\Models\Project;
use App\Models\UserDevice;
use App\Models\UserPlan;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.user_plans.index')->with('custom_title','Subscribed Users');
    }

    public function create()
    {
        $projects = Project::get();
        $users = User::with('project')->get();
        return view('admin.pages.user_plans.create',compact('projects','users'))->with(['custom_title' => 'Subscribed User']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPlanRequest $request)
    {
        $request['custom_id']   =   getUniqueString('user_plans');

        UserDevice::where('user_id',$request->user_id)->where('is_active','y')->update([
            'device_id' => $request->device_id,
            'device_type'   => $request->device_type
        ]);

        $request['user_device_id'] = UserDevice::where('user_id',$request->user_id)->where('is_active','y')->first()->id;
        $request['purchase_at'] = Carbon::now();
        $request['is_active'] = 'y';
        $user_plan = UserPlan::create($request->all());

        if( $user_plan ) {
            flash('Subscribed Users created successfully!')->success();
        } else {
            flash('Unable to add plan. Please try again later.')->error();
        }
        return redirect(route('admin.user_plans.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPlan $user_plan)
    {
        $user_plan->load('device');
        $users = User::where('project_id',$user_plan->project_id)->get();
        $projects = Project::get();
        $plans = Plan::where('project_id',$user_plan->project_id)->get();
        $user_devices = UserDevice::where('user_id',$user_plan->user_id)->get();
        return view('admin.pages.user_plans.edit', compact('user_plan','users','projects','plans','user_devices'))->with(['custom_title' => 'Subscribed User']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserPlanRequest $request, UserPlan $user_plan)
    {
        try{
            DB::beginTransaction();
            if(!empty($request->action) && $request->action == 'change_status') {
                $content = ['status'=>204, 'message'=>"something went wrong"];
                if($user_plan) {
                    $user_plan->is_active = $request->value;

                    if($user_plan->save()) {
                        DB::commit();
                        $content['status']=200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {

                UserDevice::where('user_id',$request->user_id)->where('is_active','y')->update([
                    'device_id' => $request->device_id,
                    'device_type'   => $request->device_type
                ]);
                $request['user_device_id'] = UserDevice::where('user_id',$request->user_id)->where('is_active','y')->first()->id;

                $user_plan->fill($request->all());

                if( $user_plan->save() ) {
                    DB::commit();
                    flash('Subscribed user plan updated successfully!')->success();
                } else {
                    flash('Unable to update user. Try again later')->error();
                }
                return redirect(route('admin.user_plans.index'));
            }
        }catch(QueryException $e){
            DB::rollback();
            return redirect()->back()->flash('error',$e->getMessage());
        }catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!empty($request->action) && $request->action == 'delete_all'){
        $content = ['status'=>204, 'message'=>"something went wrong"];

        $users_profile_photos = User::whereIn('custom_id', explode(',', $request->ids))->pluck('profile_photo')->toArray();
        foreach ($users_profile_photos as $image) {
            if(!empty($image)){
              Storage::delete($image);
            }
        }
        User::whereIn('custom_id',explode(',',$request->ids))->delete();
        $content['status']=200;
        $content['message'] = "User deleted successfully.";
        $content['count'] = User::all()->count();
        return response()->json($content);
        }else{
        $user = User::where('custom_id', $id)->firstOrFail();
        if( $user->profile_photo ){
        Storage::delete($user->profile_photo);
        }
        $user->delete();
        if(request()->ajax()){
        $content = array('status'=>200, 'message'=>"Subscribed user plan deleted successfully.", 'count' => User::all()->count());
        return response()->json($content);
        }else{
        flash('Subscribed user plan deleted successfully.')->success();
        return redirect()->route('admin.users.index');
        }
        }
    }


    public function listing(Request $request){
        extract($this->DTFilters($request->all()));
        $records = [];
        $user_plans = UserPlan::orderBy($sort_column, $sort_order);

        if ($search != '') {
            $user_plans->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('first_name','like',"%{$search}%")
                    ->orWhere('last_name','like',"%{$search}%");
                })->orWhareHas('plan', function ($query) use ($search) {
                    $query->where('name','like',"%{$search}%");
                })->orWhareHas('project', function ($query) use ($search) {
                    $query->where('name','like',"%{$search}%");
                });
            });
        }
        $count = $user_plans->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $user_plans = $user_plans->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $user_plans = $user_plans->with(['user','plan','project'])->get();
        foreach ($user_plans as $user_plan) {

            $params = [
                'checked' => ($user_plan->is_active == 'y' ? 'checked' : ''),
                'getaction' => $user_plan->is_active,
                'class' => '',
                'id' => $user_plan->custom_id,
            ];

            $records['data'][] = [
                'id' => $user_plan->id ?? '',
                'user_id' => $user_plan->user->first_name ?? '',
                'plan_id' => $user_plan->plan->name ?? '',
                'project_id' => $user_plan->project->name ?? '',
                'purchase_at' => $user_plan->purchase_at ?? '',
                'device_id' => $user_plan->device->device_id ?? '',
                'device_type' => $user_plan->device->device_type ?? '',
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'User', 'id' => $user_plan->custom_id], $user_plan)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $user_plan->custom_id)->render(),
            ];
        }

        return $records;
    }
}
