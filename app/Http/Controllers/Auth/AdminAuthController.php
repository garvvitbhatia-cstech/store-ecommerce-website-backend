<?php
namespace App\Http\Controllers\Auth;

use Validator;
use Hash;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Admin; 
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'auth/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin(){
        return view('admins.auth.login');
    }

    /**
     * Show the application loginprocess.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request){

		$request->validate([
			'email' => 'required',
			'password' => 'required',
		],[
			'password.required' => 'Please enter your password.',
			'email.required' => 'Please enter your email address.',
		]);
	  
        if(auth()->guard('admin')->attempt([
			'email' => $request->input('email'), 
			'password' => $request->input('password')
		])){
            $user = auth()->guard('admin')->user();
			return redirect('admins/dashboard')->with('success','You are Login successfully!!');
        }else{
            return back()->with('error','Email or password are wrong.');
        }

    }
	
	public function change_password(Request $request){		
		$postData = $request->all();
		if(isset($postData) & !empty($postData)){
			
			$request->validate([
				'old_password' => 'required',
				'new_password' => 'required',
				'confirm_password' => 'required',
			],[
				'old_password.required' => 'Please enter your old password.',
				'new_password.required' => 'Please enter your new password.',
				'confirm_password.required' => 'Please enter your confirm password.',
			]);
			
			if(trim($request->confirm_password) != trim($request->new_password)){
				return back()->with("error", "New password and confirm password must be same.");
			}			
			if(!Hash::check($request->old_password, auth('admin')->user()->password)){
				return back()->with("error", "Old Password Doesn't match!");
			}			
			Admin::whereId(auth('admin')->user()->id)->update([
				'password' => Hash::make($request->new_password)
			]);	
			return back()->with("success", "Password changed successfully!");
				
		}		
		return view('admins.auth.change_password');
	}
	
	/**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('/admins/login')->with('success','You are logout successfully!!');
    }

}