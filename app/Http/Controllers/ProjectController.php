<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;
use DataTables;
use DB;

class ProjectController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function add_project(Request $request)
    {
       
        $validator = $request->validate([
            'project_name' => 'required',
            'customer' => 'required'
        ]);
        $count = Project::count();
        $project = new Project;
        $project->project_name = $this->filter_data($request->input('project_name'));
        $project->customer_id =$this->filter_data($request->input('customer'));
        $project->project_id = "PR-0000".($count+1);
        $project->user_id = $request->user()->id;
        $project->project_address = $this->filter_data($request->input('project_address'));
        $project->postal_area = $this->filter_data($request->input('postal_area'));
        $project->postal_code = $this->filter_data($request->input('postal_code'));
        $project->project_mang_name = $this->filter_data($request->input('project_mang_name'));
        $project->project_mang_mobile = $this->filter_data($request->input('project_mang_mobile'));
        $project->project_mang_email = $this->filter_data($request->input('project_mang_email'));
        
        $project->onsite_name = $this->filter_data($request->input('onsite_name'));
        $project->onsite_mobile = $this->filter_data($request->input('onsite_mobile'));
        $project->onsite_email = $this->filter_data($request->input('onsite_email'));
        $project->project_type = $this->filter_data($request->input('project_type'));
        $project->project_status = $this->filter_data($request->input('project_status'));
        $project->property_area = $this->filter_data($request->input('property_area'));
        $project->no_of_floors = $this->filter_data($request->input('no_of_floors'));
        $project->building_year = $this->filter_data($request->input('building_year'));
        $project->last_refurbished = $this->filter_data($request->input('last_refurbished'));
        $project->env_report = $this->filter_data($request->input('env_report'));
        $project->fdv_document = $this->filter_data($request->input('fdv_document'));
        $project->ambition = $this->filter_data($request->input('ambition'));
        $project->project_start_date = $this->filter_data($request->input('project_start_date'));
        $project->project_catalog_date = $this->filter_data($request->input('project_catalog_date'));
        $project->project_avail_date = $this->filter_data($request->input('project_avail_date'));
        $project->project_avail_end_date = $this->filter_data($request->input('project_avail_end_date'));
        $project->note = $this->filter_data($request->input('note'));
        $project->billing_project_company = $this->filter_data($request->input('billing_project_company'));
        $project->billing_orgno = $this->filter_data($request->input('billing_orgno'));
        $project->billing_project_number = $this->filter_data($request->input('billing_project_number'));
        $project->billing_customer_ref = $this->filter_data($request->input('billing_customer_ref'));
        $project->billing_address = $this->filter_data($request->input('billing_address'));
        $project->billing_postal_code = $this->filter_data($request->input('billing_postal_code'));
        $project->billing_postal_area = $this->filter_data($request->input('billing_postal_area'));
        $project->credit_period = $this->filter_data($request->input('credit_period'));
        if($request->hasFile('imageFile')) {
            $image = $request->file('imageFile');
            $name = time().rand().'.'.$image->getClientOriginalExtension();
            $file_path = 'uploads/projects/';
            //$destinationPath = public_path($file_path);
            //$image->move($destinationPath, $name);
            Storage::disk('s3')->put($file_path.$name, file_get_contents($image));
            $project->project_image = $name;
        }
        $project->save();
        $project_id = $project->id;
        if($request->hasFile('imagemultiFile')){
            foreach($request->file('imagemultiFile') as $k=>$eachfile) {
                $name = pathinfo($eachfile->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.'.$eachfile->getClientOriginalExtension();
                $file_path = 'uploads/projects/documents/';
                //$destinationPath = public_path($file_path);
                //$eachfile->move($destinationPath, $name);
                Storage::disk('s3')->put($file_path.$name, file_get_contents($eachfile));
                $projectlist = new ProjectFile;
                $projectlist->category_id =  $request->input('filecategory')[$k];
                $projectlist->file_name = $name;
                $projectlist->project_id = $project_id;
                $projectlist->save();
            }
        }
        if($project_id) {
            return response()->json(['status'=>'1','message' => 'Successfully project added.', 'project_id' => $project->project_id], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in project add.'], 422);
        }
    }

    public function projects(Request $request) {
        $projects = Project::with(['customer' => function($query){
                        $query->select('customer_name', 'id', 'image_path');
                    }])->with(['projectdocs' => function($query){
                        $query->select('project_id', 'file_name');
                    }])
                    ->select('projects.*')
                    ->join('users', 'users.id', '=', 'projects.user_id')
                    ->where('users.client_id', $request->user()->client_id)
                    ->orderBy('projects.id', 'desc');

        return DataTables::eloquent($projects)
                    ->addColumn('image_base_path', 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects')
                    ->addIndexColumn('index')
                    ->editColumn('project_name', function($project) {
                        return  "<a href='/view-project/$project->id'>".$project->project_name."</a>";
                    })
                    ->addColumn('project_name_raw', function($project) {
                        return  $project->project_name;
                    })
                    ->rawColumns(['project_name'])
                    ->make();
        //return response()->json(['status'=>'1','message' => 'Project List', 'projects' => $projects, 'image_base_path' => 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects'], 200);
    }

    public function projectList(){
        $projects = Project::select('id', 'project_name') ->orderBy('project_name', 'desc')->get();
        return response()->json(['status'=>'1','message' => 'Project List', 'projects' => $projects, 'image_base_path' => 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects'], 200);
    }

    public function projectgrid($page_id){
        $limit = ($page_id-1)*12;
        $offset = 12;
        $projects = Project::select('id', 'project_name', 'project_image')->orderBy('id', 'desc')->skip($limit)->take($offset)->get();
        return response()->json(['status'=>'1','message' => 'Project List', 'projects' => $projects, 'image_base_path' => 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects'], 200);
    }

    public function search_project(Request $request) {
        DB::enableQueryLog();
        $search_text = $request->input('query');
        $projects = Project::with(['customer' => function($query){
                                $query->select('name as customer_name', 'id', 'image_path');
                            }])->with(['projectdocs' => function($query){
                                $query->select('project_id', 'file_name');
                            }])
                    ->where('status', '=', '1')
                    ->where(function($query) use ($search_text) {
                        $query->orWhere('project_name', 'Like', '%'.$search_text.'%')
                        ->orWhere('note', 'Like', '%'.$search_text.'%')
                        ->orWhere('project_type', 'Like', '%'.$search_text.'%');
                     })
                     ->get();
        return response()->json(['status'=>'1','message' => 'Project List', 'projects' => $projects, 'image_base_path' => 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects'], 200);
    }

    public function get_project_info($id){
        $project = Project::with(['customer' => function($query){
            $query->select('name as customer_name', 'id', 'image_path');
        }])->with(['projectdocs' => function($query){
            $query->select('id', 'category_id', 'project_id', 'file_name')->with('projectCategory');
        }])
        ->where('id', $id)
        ->first();
        return response()->json(['status'=>'1','message' => 'project info', 'project' => $project, 'image_base_path' => 'https://resources-products-new.s3.ap-south-1.amazonaws.com/uploads/projects'], 200);
    }

    public function edit_project(Request $request)
    {
       
        $validator = $request->validate([
            'project_name' => 'required',
            'customer' => 'required'
        ]);
        
        $project = Project::find($request->input('id'));
        $project->project_name = $this->filter_data($request->input('project_name'));
        $project->customer_id =$this->filter_data($request->input('customer'));
        $project->user_id = $request->user()->id;
        $project->project_address = $this->filter_data($request->input('project_address'));
        $project->postal_area = $this->filter_data($request->input('postal_area'));
        $project->postal_code = $this->filter_data($request->input('postal_code'));
        $project->project_mang_name = $this->filter_data($request->input('project_mang_name'));
        $project->project_mang_mobile = $this->filter_data($request->input('project_mang_mobile'));
        $project->project_mang_email = $this->filter_data($request->input('project_mang_email'));
        
        $project->onsite_name = $this->filter_data($request->input('onsite_name'));
        $project->onsite_mobile = $this->filter_data($request->input('onsite_mobile'));
        $project->onsite_email = $this->filter_data($request->input('onsite_email'));
        $project->project_type = $this->filter_data($request->input('project_type'));
        $project->project_status = $this->filter_data($request->input('project_status'));
        $project->property_area = $this->filter_data($request->input('property_area'));
        $project->no_of_floors = $this->filter_data($request->input('no_of_floors'));
        $project->building_year = $this->filter_data($request->input('building_year'));
        $project->last_refurbished = $this->filter_data($request->input('last_refurbished'));
        $project->env_report = $this->filter_data($request->input('env_report'));
        $project->fdv_document = $this->filter_data($request->input('fdv_document'));
        $project->ambition = $this->filter_data($request->input('ambition'));
        $project->project_start_date = $this->filter_data($request->input('project_start_date'));
        $project->project_catalog_date = $this->filter_data($request->input('project_catalog_date'));
        $project->project_avail_date = $this->filter_data($request->input('project_avail_date'));
        $project->project_avail_end_date = $this->filter_data($request->input('project_avail_end_date'));
        $project->note = $this->filter_data($request->input('note'));
        $project->billing_project_company = $this->filter_data($request->input('billing_project_company'));
        $project->billing_orgno = $this->filter_data($request->input('billing_orgno'));
        $project->billing_project_number = $this->filter_data($request->input('billing_project_number'));
        $project->billing_customer_ref = $this->filter_data($request->input('billing_customer_ref'));
        $project->billing_address = $this->filter_data($request->input('billing_address'));
        $project->billing_postal_code = $this->filter_data($request->input('billing_postal_code'));
        $project->billing_postal_area = $this->filter_data($request->input('billing_postal_area'));
        $project->credit_period = $this->filter_data($request->input('credit_period'));
        if($request->hasFile('imageFile')) { 
            $image = $request->file('imageFile');
            $name = time().rand().'.'.$image->getClientOriginalExtension();
            $file_path = 'uploads/projects/';
            //$destinationPath = public_path($file_path);
            //$image->move($destinationPath, $name);
            Storage::disk('s3')->put($file_path.$name, file_get_contents($image));
            $project->project_image = $name;
        }
        $project->save();
        $project_id = $project->id;
        if($request->hasFile('imagemultiFile')){
            foreach($request->file('imagemultiFile') as $k=>$eachfile) {
                $name = pathinfo($eachfile->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.'.$eachfile->getClientOriginalExtension();
                $file_path = 'uploads/projects/documents/';
                //$destinationPath = public_path($file_path);
                //$eachfile->move($destinationPath, $name);
                Storage::disk('s3')->put($file_path.$name, file_get_contents($eachfile));
                $projectlist = new ProjectFile;
                $projectlist->category_id =  $request->input('filecategory')[$k];
                $projectlist->file_name = $name;
                $projectlist->project_id = $project_id;
                $projectlist->save();
            }
        }
        if($project_id) {
            return response()->json(['status'=>'1','message' => 'Successfully project edited.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in project edit.'], 422);
        }
    }

    public function deleteProject($id){
        Project::where('id', '=', $id)->delete();
        return response()->json(['status'=>'1','message' => 'Project deleted Successfully'], 200);
    }

    public function delete_project_doc($id){
        ProjectFile::where('id', $id)->delete();
        return response()->json(['status'=>'1','message' => 'Selected Project Document deleted Successfully'], 200);
    }

    public function filter_data($input){
        if(strtolower($input) == 'null') {
            return NULL;
        }
        return $input;
    }

}