<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class UserPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.user_plans.index')->with('custom_title','User Plan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'User', 'id' => $user_plan->custom_id], $user_plan)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $user_plan->custom_id)->render(),
            ];
        }

        return $records;
    }
}
