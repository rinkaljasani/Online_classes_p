<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Models\Project;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PlansController extends Controller
{
    public function index()
    {
        $projects = Project::get();
        return view('admin.pages.plans.index',compact('projects'))->with(['custom_title' => 'Plans']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects= Project::get();
        return view('admin.pages.plans.create',compact('projects'))->with(['custom_title' => 'Plan']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {

        $request['custom_id']   =   getUniqueString('plans');
        $request['active_at'] = now();
        $request['is_active'] = 'y';
        $request['project_id'] = Project::whereCustomId($request->project_id)->first(['id'])->id;

        $path = NULL;
        if( $request->has('image') ) {
            $path = $request->file('image')->store('plans/image');
        }
        $plan = Plan::create($request->all());
        $plan->image = $path;
        if( $plan->save() ) {
            flash('Plan created successfully!')->success();
        } else {
            flash('Unable to save image. Please try again later.')->error();
        }
        return redirect(route('admin.plans.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view('admin.pages.plans.edit', compact('plan'))->with(['custom_title' => 'Plans']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanRequest $request, Plan $plan)
    {
        try{
            DB::beginTransaction();
            if(!empty($request->action) && $request->action == 'change_status') {
                $content = ['status'=>204, 'message'=>"something went wrong"];
                if($plan) {
                    $plan->is_active = $request->value;
                    if($plan->save()) {
                        DB::commit();
                        $content['status']=200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {

                $plan->fill($request->all());
                if( $plan->save() ) {
                    DB::commit();
                    flash('Plan details updated successfully!')->success();
                } else {
                    flash('Unable to update plan. Try again later')->error();
                }
                return redirect(route('admin.plans.index'));
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

        Plan::whereIn('custom_id',explode(',',$request->ids))->delete();
        $content['status']=200;
        $content['message'] = "Plan deleted successfully.";
        $content['count'] = Plan::all()->count();
        return response()->json($content);
        }else{
        $plan = Plan::where('custom_id', $id)->firstOrFail();
        $plan->delete();
        if(request()->ajax()){
        $content = array('status'=>200, 'message'=>"Plan deleted successfully.", 'count' => Plan::all()->count());
        return response()->json($content);
        }else{
        flash('Plan deleted successfully.')->success();
        return redirect()->route('admin.Plans.index');
        }
        }
    }

    public function listing(Request $request)
    {
        extract($this->DTFilters($request->all()));

        $records = [];
        $plans = Plan::orderBy($sort_column, $sort_order);
        if ($search != '') {
            $plans->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('months', 'like', "%{$search}%")
                ->orWhere('special_offer_months', 'like', "%{$search}%");
            });
        }

        if($request->project_id != ''){
            $project = Project::where('custom_id',$request->project_id)->first();
            $plans->where('project_id', $project->id);
        }
        $count = $plans->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $plans = $plans->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $plans = $plans->get();
        foreach ($plans as $plan) {

            $params = [
                'checked' => ($plan->is_active == 'y' ? 'checked' : ''),
                'getaction' => $plan->is_active,
                'class' => '',
                'id' => $plan->custom_id,
            ];

            $records['data'][] = [
                'id' => $plan->id,
                'name' => $plan->name ?? "",
                'project' => $plan->project ? $plan->project->name : '',
                'months' => $plan->months ?? "",
                'price' => $plan->price ?? "",
                'special_offer_months' => $plan->special_offer_months ?? "",
                'prorities' => $plan->prorities ?? null,
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'Plan', 'id' => $plan->custom_id], $plan)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $plan->custom_id)->render(),
            ];
        }

        return $records;
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.pages.users.trashed', compact('users'))->with(['custom_title' => 'TRASHED']);
    }

    public function trashedData(Request $request)
    {
        extract($this->DTFilters($request->all()));
        $records = [];
        $users = User::orderBy($sort_column, $sort_order);

        if ($search != '') {
            $users->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('contact_no', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $count = $users->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $users = $users->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $users = $users->onlyTrashed()->get();
        foreach ($users as $user) {

            $params = [
                'checked' => ($user->is_active == 'y' ? 'checked' : ''),
                'display' => ($user->is_display == 'y' ? 'checked' : ''),
                'getaction' => $user->is_active,
                'class' => '',
                'id' => $user->id,
            ];

            $records['data'][] = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => '<a href="mailto:' . $user->email . '" >' . $user->email . '</a>',
                'contact_no' => $user->contact_no ? '<a href="tel:' . $user->contact_no . '" >' . $user->contact_no . '</a>' : 'N/A',
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'display' => view('admin.layouts.includes.switchDisplay', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'User', 'id' => $user->id], $user)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $user->id)->render(),
            ];
        }
        // dd($records);

        return $records;
    }
}
