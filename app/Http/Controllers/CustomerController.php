<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use DataTables;
use DB;
use Illuminate\Support\Facades\App;
class CustomerController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function add_customer(Request $request)
    {
        $validator = $request->validate([
            'customerName' => 'required',
            //'orgname' => 'required',
            // 'address' => '',
            // 'postal_code' => '',
            // 'postal_area' => '',
            // 'country' => '',
            // 'name' => '',
            // 'mobile' => '',
           // 'email' => '|email'
           // 'email' => 'email'
        ]);
        $count = Customer::count();
        $customer = new Customer;
        $customer->customer_name = $this->filter_data($request->input('customerName'));
        $customer->user_id = $this->filter_data($request->user()->id);
        $customer->org_number = $this->filter_data($request->input('orgname'));
        $customer->customer_id = "CU-0000".($count+1);
        $customer->country = $this->filter_data($request->input('country'));
if( $this->filter_data($request->input('address') )!= 'undefined'){
        $customer->address = $this->filter_data($request->input('address'));
}
if($this->filter_data($request->input('postal_code'))!='undefined'){
        $customer->postal_code = $this->filter_data($request->input('postal_code'));
}
if($this->filter_data($request->input('postal_area'))!='undefined'){
        $customer->postal_area = $this->filter_data($request->input('postal_area'));
}
        $customer->name = $this->filter_data($request->input('name'));
        $customer->email = $this->filter_data($request->input('email'));
        $customer->mobile_number = $this->filter_data($request->input('mobile'));
        $customer->note = $this->filter_data($request->input('note'));
        $customer->status = 1;
        if($request->hasFile('imageFile')) {
            $image = $request->file('imageFile');
            $name = time().rand().'.'.$image->getClientOriginalExtension();
            $file_path = 'uploads/customers/';
            // $destinationPath = public_path($file_path);
            // $image->move($destinationPath, $name);
            Storage::disk('s3')->put($file_path.$name, file_get_contents($image));
            $customer->image_path = $name;
        }
        if($customer->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully customer added.', 'id' => $customer->id], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in customer add.'], 422);
        }
    }

    public function customers(Request $request) {
        $path=config('app.image_bucket');
        $customers = Customer::where('customers.status', '=', '1')->withCount(['projects'])
                        ->with(['projects'=> function($query){
                            $query->select('id', 'customer_id')->withCount('products');
                        }])
                        ->join('users', 'users.id', '=', 'customers.user_id')
                        ->where('users.client_id', $request->user()->client_id);
						// ->orderBy('customers.id', 'desc');
        return DataTables::eloquent($customers)
                        ->addColumn('image_base_path', ''.$path.'/uploads/customers')
                        ->addIndexColumn('index')
                        ->editColumn('customer_name', function($eachcutomer) {
                            return  "<a href='/view-customer/$eachcutomer->id'>".$eachcutomer->customer_name."</a>";
                        })
                        ->editColumn('customer_name_raw', function($eachcutomer) {
                            return  $eachcutomer->customer_name;
                        })
                        ->addColumn('products_count', function($eachcutomer) {
                            $product_count = 0;
                            if(count($eachcutomer['projects']) > 0) {
                                foreach($eachcutomer['projects'] as $eachproject){
                                    $product_count = $product_count + $eachproject->products_count;
                                }
                            }
                            return $product_count;
                        })
                        ->rawColumns(['products_count', 'customer_name'])
                        ->make();
        // if(count($customers) > 0) {
        //     foreach($customers as $k=>$eachcutomer) {
        //         $product_count = 0;
        //         if(count($eachcutomer['projects']) > 0) {
        //             foreach($eachcutomer['projects'] as $eachproject){
        //                 $product_count = $product_count + $eachproject->products_count;
        //             }
        //         }
        //         $customers[$k]['products_count'] = $product_count;
        //     }
        // }
        return response()->json(['status'=>'1','message' => 'Customer List', 'customers' => $customers, 'image_base_path' => ''.$path.'/uploads/customers/'], 200);
    }

    public function customersList() {
        $path=config('app.image_bucket');
        $customers = Customer::where('status', '=', '1')->select('customer_name', 'id')->orderBy('customer_name', 'asc')->get();
        return response()->json(['status'=>'1','message' => 'Customer List', 'customers' => $customers, 'image_base_path' => ''.$path.'/uploads/customers/'], 200);
    }

    public function customersgrid($page_id) {
        $path=config('app.image_bucket');
        $limit = ($page_id-1)*12;
        $offset = 12;
        $customers = Customer::where('status', '=', '1')->select('customer_name', 'id', 'image_path')->orderBy('updated_at', 'desc')->skip($limit)->take($offset)->get();
        return response()->json(['status'=>'1','message' => 'Customer List', 'customers' => $customers, 'image_base_path' => ''.$path.'/uploads/customers/'], 200);
    }
    

    public function get_customer_info($id) {
        $path=config('app.image_bucket');
        $customer = Customer::where('id', '=', $id)->first();
        return response()->json(['status'=>'1','message' => 'Client Information', 'customer' => $customer, 'image_base_path' => ''.$path.'/uploads/customers/'], 200);
    }

    public function search_customer(Request $request) {
        $path=config('app.image_bucket');
        //DB::enableQueryLog();
        $search_text = $request->input('query');
        $customers = Customer::where('status', '=', '1')
                     ->where(function($query) use ($search_text) {
                        $query->orWhere('org_number', 'Like', '%'.$search_text.'%')
                        ->orWhere('name', 'Like', '%'.$search_text.'%')
                        ->orWhere('customer_name', 'Like', '%'.$search_text.'%')
                        ->orWhere('email', 'Like', '%'.$search_text.'%')
                        ->orWhere('postal_code', 'Like', '%'.$search_text.'%')
                        ->orWhere('postal_area', 'Like', '%'.$search_text.'%')
                        ->orWhere('mobile_number', 'Like', '%'.$search_text.'%');
                     })
                     ->get();
                     //print_r(DB::getQueryLog());
        return response()->json(['status'=>'1','message' => 'Customer List', 'customers' => $customers, 'image_base_path' => ''.$path.'/uploads/customers/'], 200);
    }

    public function edit_customer(Request $request){
        $path=config('app.image_bucket');
        $validator = $request->validate([
            'customerName' => 'required',
            // 'orgname' => 'required',
             'id' => 'required',
            // 'address' => '',
            // 'postal_code' => '',
            // 'postal_area' => '',
            // 'country' => '',
            // 'name' => '',
            // 'mobile' => '',
            // 'email' => 'email'
            //  'email' => 'email'
        ]);

        $customer = Customer::find($request->input('id'));
        $customer->customer_name = $this->filter_data($request->input('customerName'));
        $customer->user_id = $request->user()->id;
        $customer->org_number = $this->filter_data($request->input('orgname'));

        $customer->address = $this->filter_data($request->input('address'));

        $customer->country = $this->filter_data($request->input('country'));
if( $this->filter_data($request->input('address'))!='undefined'){
        $customer->address = $this->filter_data($request->input('address'));
}

if( $this->filter_data($request->input('postal_code'))!='undefined'){
        $customer->postal_code = $this->filter_data($request->input('postal_code'));
}

if( $this->filter_data($request->input('postal_area'))!='undefined'){
        $customer->postal_area = $this->filter_data($request->input('postal_area'));
}
        $customer->name = $this->filter_data($request->input('name'));
        $customer->email = $this->filter_data($request->input('email'));
        $customer->mobile_number = $this->filter_data($request->input('mobile'));
        $customer->note = $this->filter_data($request->input('note'));
        $customer->status = 1; 
        if($request->hasFile('imageFile')) {
            $image = $request->file('imageFile');
            $name = time().rand().'.'.$image->getClientOriginalExtension();
            $file_path = 'uploads/customers/';
            //$destinationPath = public_path($file_path);
            //$image->move($destinationPath, $name);
            Storage::disk('s3')->put($file_path.$name, file_get_contents($image));
            $customer->image_path = $name;
        }
        if($customer->save()) {
            return response()->json(['status'=>'1','customer' =>$request->input('id'),'data' =>$customer,'message' => 'Successfully customer edited.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in customer edit.'], 422);
        }

    }

    public function deleteCustomer($id){
        Customer::where('id', '=', $id)->delete();
        return response()->json(['status'=>'1','message' => 'Customer deleted Successfully'], 200);
    }

    public function filter_data($input){
        if(strtolower($input) == 'null') {
            return NULL;
        }
        return $input;
    }

}