<?php
namespace App\Http\Controllers\Admins;
 
use Hash;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider; 
use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
 
class UsersController extends Controller{

	public function users(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('full_name') != ''){
			$cond['full_name'] = array('full_name', 'like', '%'.$request->input('full_name').'%');
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = User::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.users.customers',compact('pages'));
	}

	public function add_user(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'full_name' => 'required',
				'email' => 'required',
				'contact' => 'required|numeric',
				'password' => 'required'
			],[
				'full_name.required' => 'Please enter your name',
				'email.required' => 'Please enter your email',
				'contact.required' => 'Please enter your contact',
				'contact.numeric' => 'Please enter valid contact',
				'password.required' => 'Please enter your password',
			]);
			
			$status = 0;
			if(isset($request->status)){
				$status = 1;	
			}

			$user = new User;
			$user->type = 'User';
			$user->full_name = $request->full_name;
			$user->email = $request->email;
			$user->password = Crypt::encrypt($request->password);
			$user->contact = $request->contact;
			$user->address = $request->address;
			$user->status = $status;
			$user->save();

			return redirect('admins/users')->with('success','User has been created successfully');
		}
		return view('admins.users.add_customer');	
	}
	
	public function edit_user(Request $request, $id){
		$user = User::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'full_name' => 'required',
				'email' => 'required',
				'contact' => 'required|numeric',
				'password' => 'required'
			],[
				'full_name.required' => 'Please enter your name',
				'email.required' => 'Please enter your email',
				'contact.required' => 'Please enter your contact',
				'contact.numeric' => 'Please enter valid contact',
				'password.required' => 'Please enter your password',
			]);
			
			$status = 0;
			if(isset($request->status) && $request->status == 1){
				$status = 1;
			}
			$user = User::find($user->id);
			$user->type = 'User';
			$user->full_name = $request->full_name;
			$user->email = $request->email;
			$user->contact = $request->contact;
			$user->address = $request->address;
			$user->password = Crypt::encrypt($request->password);
			$user->status = $status;
			$user->save();
			return back()->with('success','Customer has been updated successfully');		
		}
		
		if(!empty($user)){
			return view('admins.users.edit_customer',compact('user'));
		}else{
			return redirect('admins/users');
		}
		
	}

}