<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FAQsRequest;
use App\Models\Faqs;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FAQsController extends Controller
{
    public function index()
    {
        $projects = Project::get();
        return view('admin.pages.faqs.index',compact('projects'))->with(['custom_title' => 'FAQs']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects= Project::get();
        return view('admin.pages.faqs.create',compact('projects'))->with(['custom_title' => 'FAQs']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FAQsRequest $request)
    {

        $request['custom_id']   =   getUniqueString('faqs');
        $request['is_active'] = 'y';
        $request['project_id'] = Project::whereCustomId($request->project_id)->first(['id'])->id;

        $faqs = Faqs::create($request->all());

        if( $faqs->save() ) {
            flash('FAQs created successfully!')->success();
        } else {
            flash('Unable to save image. Please try again later.')->error();
        }
        return redirect(route('admin.faqs.index'));
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
    public function edit(Faqs $faq)
    {
        return view('admin.pages.faqs.edit', compact('faq'))->with(['custom_title' => 'FAQs']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FAQsRequest $request, Faqs $faq)
    {

        try{
            DB::beginTransaction();
            if(!empty($request->action) && $request->action == 'change_status') {
                $content = ['status'=>204, 'message'=>"something went wrong"];
                if($faq) {
                    $faq->is_active = $request->value;
                    if($faq->save()) {
                        DB::commit();
                        $content['status']=200;
                        $content['message'] = "Status updated successfully.";
                    }
                }
                return response()->json($content);
            } else {

                $faq->fill($request->all());
                if( $faq->save() ) {
                    DB::commit();
                    flash('faqs details updated successfully!')->success();
                } else {
                    flash('Unable to update Faqs. Try again later')->error();
                }
                return redirect(route('admin.faqs.index'));
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

        Faqs::whereIn('custom_id',explode(',',$request->ids))->delete();
        $content['status']=200;
        $content['message'] = "Plan deleted successfully.";
        $content['count'] = Faqs::all()->count();
        return response()->json($content);
        }else{
        $faq = Faqs::where('custom_id', $id)->firstOrFail();
        $faq->delete();
        if(request()->ajax()){
        $content = array('status'=>200, 'message'=>"Plan deleted successfully.", 'count' => Faqs::all()->count());
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
        $faqs = Faqs::orderBy($sort_column, $sort_order);
        if ($search != '') {
            $faqs->where(function ($query) use ($search) {
                $query->where('question', 'like', "%{$search}%");
                $query->orWhereHas('project',function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            });
        }

        if($request->project_id != ''){
            $project = Project::where('custom_id',$request->project_id)->first();
                $faqs->where('project_id', $project->id);
        }

        $count = $faqs->count();

        $records['recordsTotal'] = $count;
        $records['recordsFiltered'] = $count;
        $records['data'] = [];

        $faqs = $faqs->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order);

        $faqs = $faqs->get();

        foreach ($faqs as $faq) {

            $params = [
                'checked' => ($faq->is_active == 'y' ? 'checked' : ''),
                'getaction' => $faq->is_active,
                'class' => '',
                'id' => $faq->custom_id,
            ];

            $records['data'][] = [
                'id' => $faq->id,
                'question' => $faq->question,
                'project' => $faq->project->name,
                'active' => view('admin.layouts.includes.switch', compact('params'))->render(),
                'action' => view('admin.layouts.includes.actions')->with(['custom_title' => 'Faqs', 'id' => $faq->custom_id], $faq)->render(),
                'checkbox' => view('admin.layouts.includes.checkbox')->with('id', $faq->custom_id)->render(),
            ];
        }

        return $records;
    }
}
