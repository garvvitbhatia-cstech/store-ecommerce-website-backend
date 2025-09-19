<?php
namespace App\Http\Controllers\Admins;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Models\Settings;

class AdminsController extends Controller{
    
	public function index(Request $request){
		$sessionID = auth('admin')->user()->id;
		$record = Admin::find($sessionID);
		
		$postData = $request->all();
		if(isset($postData) && !empty($postData)){
			$request->validate([
				'name' => 'required',
				'email' => 'required',
				'contact' => 'required|numeric',
				'address' => 'required'
			],[
				'name.required' => 'Please enter your name.',
				'email.required' => 'Please enter your email address.',
				//'email.unique' => 'Email address already exists.',
				'contact.required' => 'Please enter your contact.',
				'contact.numeric' => 'Mobile number should be numeric.',
				'address.required' => 'Please enter your address.',
			]);
			
			try {
				$sessionID = auth('admin')->user()->id;
				$admin = Admin::find($sessionID);
				$admin->name = $request->name;
				$admin->email = $request->email;
				$admin->contact = $request->contact;
				$admin->address = $request->address;
				$admin->profile = $request->profile_pic;
				$admin->save();
				
				return redirect()->back()->with('success','User profile has been updated successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}				
		return view('admins.admin.index',compact('record'));
	}
		
	public function change_profile(Request $request){
		if($request->ajax()){
			$setData = array();
			$msg = [];
			$postData = $request->all();
			if(isset($postData) && !empty($postData)){
				$actual_image_name = time().rand().'.'.$request->UserProfile->extension();  
				$destination = base_path().'/public/assets/images/admin/';
				if($request->UserProfile->move($destination, $actual_image_name)){
					if($request->input('old_banner') != ""){
						if(file_exists($destination.$request->input('old_banner'))){
							unlink($destination.$request->input('old_banner'));
						}
					}
					$msg['path'] = asset('public/assets/images/admin/'.$actual_image_name);
					$msg['name'] = $actual_image_name;
					$msg['msg'] = 'Success';
				}else{
					$msg['msg'] = 'Error';
				}				
			/* 
				Write Code Here for
				Store $imageName name in DATABASE from HERE 
			*/      
				
			}			
			echo json_encode(array('data'=>$msg));
		}
		exit;
	}
	
	public function setting(Request $request){
		$setting = Settings::find(1);		
		$postData = $request->all();
		if(isset($postData) && !empty($postData)){
			$request->validate([
				'admin_email' => 'required|email',
				'company_name' => 'required',
				'mobile' => 'required|numeric',
				'footer_content' => 'required'
			],[
				'admin_email.required' => 'Please enter your name.',
				'admin_email.email' => 'Please enter valid email.',
				'company_name.required' => 'Please enter company name.',
				'mobile.required' => 'Please enter your contact.',
				'mobile.numeric' => 'Mobile number should be numeric.',
				'footer_content.required' => 'Please enter footer content.',
			]);
			
			try {
				$setting = Settings::find(1);
				$setting->admin_email = $request->admin_email;
				$setting->company_name = $request->company_name;
				$setting->business_address = $request->business_address;
				$setting->business_address2 = $request->business_address2;
				$setting->mobile = $request->mobile;
				$setting->logo = $request->company_logo;
				$setting->footer_content = $request->footer_content;
				$setting->save();
				
				return redirect()->back()->with('success','Account setting has been updated successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		return view('admins.admin.setting',compact('setting'));
	}
	
	public function change_logo(Request $request){
		if($request->ajax()){
			$setData = array();
			$msg = [];
			$postData = $request->all();
			if(isset($postData) && !empty($postData)){
				$actual_image_name = time().rand().'.'.$request->CompanyLogo->extension();  
				$destination = base_path().'/public/assets/images/admin/';
				if($request->CompanyLogo->move($destination, $actual_image_name)){
					if($request->input('old_banner') != ""){
						if(file_exists($destination.$request->input('old_banner'))){
							unlink($destination.$request->input('old_banner'));
						}
					}
					$msg['path'] = asset('public/assets/images/admin/'.$actual_image_name);
					$msg['name'] = $actual_image_name;
					$msg['msg'] = 'Success';
				}else{
					$msg['msg'] = 'Error';
				}				
			/* 
				Write Code Here for
				Store $imageName name in DATABASE from HERE 
			*/      
				
			}			
			echo json_encode(array('data'=>$msg));
		}
		exit;
	}
	
	public function dashboard(){
		return view('admins.admin.dashboard');
	}

}