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
        return view('admin.pages.user_plans.index')->with('custom_title', 'Subscribed Users');
    }

    public function create()
    {
        $projects = Project::get();
        $users = User::with('project')->get();
        return view('admin.pages.user_plans.create', compact('projects', 'users'))->with(['custom_title' => 'Subscribed User']);
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

        UserDevice::where('user_id', $request->user_id)->where('is_active', 'y')->update([
            'device_id' => $request->device_id,
            'device_type'   => $request->device_type
        ]);
        $plan = Plan::where('id', $request->plan_id)->first();

        $isPLanExists = UserPlan::where('user_id', $request->user_id)->where('plan_id', $request->plan_id)->whereHas('device', function ($query) use ($request) {
            $query->where('device_id', $request->device_id);
        })->latest()->first();
        // dd($isPLanExists,'$isPLanExists',$request->all());
        $request['purchase_at'] = Carbon::now();
        $request['is_active'] = 'y';
        $request['expiry_at'] = Carbon::now()->addMonths(($plan->months ?? 0) + ($plan->special_offer_months ?? 0));
        // dump($isPLanExists);
        $abc = $isPLanExists;
        if ($abc) {

            $request['purchase_at'] = Carbon::parse($abc->expiry_at)->addDay();
            $request['expiry_at'] = Carbon::parse(Carbon::parse($abc->expiry_at)->addDay())->addMonths(($plan->months ?? 0) + ($plan->special_offer_months ?? 0));
        }
        $request['user_device_id'] = UserDevice::where('user_id', $request->user_id)->where('is_active', 'y')->first()->id;
        // dd($request->all());
        $user_plan = UserPlan::create($request->all());

        if ($user_plan) {
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
        $users = User::where('project_id', $user_plan->project_id)->get();
        $projects = Project::get();
        $plans = Plan::where('project_id', $user_plan->project_id)->get();
        $user_devices = UserDevice::where('user_id', $user_plan->user_id)->get();
        return view('admin.pages.user_plans.edit', compact('user_plan', 'users', 'projects', 'plans', 'user_devices'))->with(['custom_title' => 'Subscribed User']);
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
        try {
            DB::beginTransaction();
            if (!empty($request->action) && $request->action == 'change_status') {
                $content = ['status' => 204, 'message' => "something went wrong"];
                if ($user_plan) {
                    $user_plan->is_active = $request->value;

                    if ($user_plan->save()) {
                        DB::commit();
                        $content['status'] = 200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {
                UserDevice::where('user_id', $user_plan->user_id)->where('is_active', 'y')->update([
                    'device_id' => $request->device_id,
                    'device_type'   => $request->device_type
                ]);
                $request['user_device_id'] = UserDevice::where('user_id', $user_plan->user_id)->where('is_active', 'y')->first()->id;

                // $isPlanExists = UserPlan::where('user_device_id',$request->device_id)->where('plan_id',$request->plan_id)->first();


                $user_plan->fill($request->all());

                if ($user_plan->save()) {
                    // Update Plan expiry date if the plan is Updated

                    // if ($user_plan->wasChanged('plan_id')) {
                    //     // dd('in');

                    //     // Check plan is expired or not
                    //     if (Carbon::now()->lt($user_plan->expiry_at)) {
                    //         // Get the Remaining Months to expire the plan
                    //         $extra_plan_months = Carbon::now()->diffInMonths($user_plan->expiry_at);
                    //     }
                    //     // Update the Plan expiry
                    //     $user_plan->update([
                    //         'expiry_at' => Carbon::now()->addMonths(($user_plan->plan->months ?? 0) + ($user_plan->plan->special_offer_months ?? 0) + $extra_plan_months ?? 0),
                    //     ]);
                    // }
                    DB::commit();
                    flash('Subscribed user plan updated successfully!')->success();
                } else {
                    flash('Unable to update user. Try again later')->error();
                }

                return redirect(route('admin.user_plans.index'));
            }
        } catch (QueryException $e) {
            DB::rollback();
            return redirect()->back()->flash('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        if (!empty($request->action) && $request->action == 'delete_all') {
            $content = ['status' => 204, 'message' => "something went wrong"];

            UserPlan::whereIn('custom_id', explode(',', $request->ids))->delete();
            $content['status'] = 200;
            $content['message'] = "Subscribed Users deleted successfully.";
            $content['count'] = UserPlan::all()->count();
            return response()->json($content);
        } else
            $user_plan = UserPlan::where('custom_id', $id)->firstOrFail();
        $user_plan->delete();
        if (request()->ajax()) {
            $content = array('status' => 200, 'message' => "Subscribed user plan deleted successfully.", 'count' => UserPlan::all()->count());
            return response()->json($content);
        } else {
            flash('Subscribed user plan deleted successfully.')->success();
            return redirect()->route('admin.user_plans.index');
        }
    }



    public function listing(Request $request)
    {
        extract($this->DTFilters($request->all()));
        $records = [];
        $user_plans = UserPlan::orderBy($sort_column, $sort_order);

        if ($search != '') {
            $user_plans->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhareHas('plan', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhareHas('project', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            });
        }
        $count = $user_plans->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $user_plans = $user_plans->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $user_plans = $user_plans->with(['user', 'plan', 'project'])->get();
        foreach ($user_plans as $user_plan) {

            $params = [
                'checked' => ($user_plan->is_active == 'y' ? 'checked' : ''),
                'getaction' => $user_plan->is_active,
                'class' => '',
                'id' => $user_plan->custom_id,
            ];

            $records['data'][] = [
                'id' => $user_plan->id ?? '',
                'user_id' => ($user_plan->user->first_name ?? '') . '(' . $user_plan->user->email . ')',
                'plan_id' => $user_plan->plan->name ?? '',
                'project_id' => $user_plan->project->name ?? '',
                'purchase_at' => $user_plan->purchase_at ?? '',
                'device_id' => $user_plan->device->device_id ?? '',
                'device_type' => $user_plan->device->device_type ?? '',
                'purchase_at' => Carbon::parse($user_plan->purchase_at)->format('d M Y') ?? '',
                'expiry_at' => Carbon::parse($user_plan->expiry_at)->format('d M Y') ?? '',
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'User', 'id' => $user_plan->custom_id], $user_plan)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $user_plan->custom_id)->render(),
            ];
        }

        return $records;
    }
}
