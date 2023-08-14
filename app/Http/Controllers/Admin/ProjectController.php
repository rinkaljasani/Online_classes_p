<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.pages.project.index')->with(['custom_title' => 'Project']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.pages.project.edit', compact('project'))->with(['custom_title' => 'Projects']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        try{
            DB::beginTransaction();
            if(!empty($request->action) && $request->action == 'change_status') {
                $content = ['status'=>204, 'message'=>"something went wrong"];
                if($project) {
                    $project->is_active = $request->value;
                    if($project->save()) {
                        DB::commit();
                        $content['status']=200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {

                $project->fill($request->all());
                if( $project->save() ) {
                    DB::commit();
                    flash('Plan details updated successfully!')->success();
                } else {
                    flash('Unable to update plan. Try again later')->error();
                }
                return redirect(route('admin.projects.index'));
            }
        }catch(QueryException $e){
            DB::rollback();
            return redirect()->back()->flash('error',$e->getMessage());
        }catch(Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function listing(Request $request)
    {

        extract($this->DTFilters($request->all()));
        $records = [];
        $projects = Project::orderBy($sort_column, $sort_order);

        if ($search != '') {
            $projects->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        $count = $projects->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $projects = $projects->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $projects = $projects->get();

        foreach ($projects as $project) {

            $params = [
                'checked' => ($project->is_active == 'y' ? 'checked' : ''),
                'getaction' => $project->is_active,
                'class' => '',
                'id' => $project->custom_id,
            ];

            $records['data'][] = [
                'id' => $project->id,
                'name' => $project->name,
                'created_at' => $project->created_at->format('D-m-Y'),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'Plan', 'id' => $project->custom_id], $project)->render(),
            ];
        }
        return $records;
    }

}
