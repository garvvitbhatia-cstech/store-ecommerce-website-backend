<?php
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Illuminate\Validation\Rule;
use ReallySimpleJWT\Token;
use App\Models\User;
use App\Models\Responses;
use Session;
 
class CustomerController extends Controller{
	
	private static $UserModel;
	public function __construct(){
		self::$UserModel = new User();
	}

	public function Login(Request $request){

        $validator = Validator::make($request->all(), [
			'username' => 'required',
		],[
			'username.required' => 'Please enter your username.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('username')){
				return response()->json(['success'=>false, 'message' => $errors->first('username')]);
			}
		}else{

            if(!self::$UserModel->GetByUsername($request->input('username'))){
                return response()->json(['success'=>false,'message'=>Responses::GetResponse('USER.INVALID_CREDENTIALS')],200);
            }

            $User = self::$UserModel->GetByUsername($request->input('username'));

            if($request->post('password') && !empty($request->post('password'))){
                $Password = password_hash($request->post('password'),PASSWORD_BCRYPT);
                $PasswordMatch = password_verify($request->post('password'), $User->password);
                if(!$PasswordMatch){
                    return response()->json(['success'=>false,'message'=>Responses::GetResponse('USER.INVALID_CREDENTIALS')],200);
                }
            }
            if($User->status != 1){
                return response()->json(['success'=>false,'message'=>Responses::GetResponse('USER.ACCOUNT_INACTIVE')],200);
            }
            if($request->post('password') && !empty($request->post('password'))){
                $userId = $User->id;
                $secret = env('JWT_KEY');
                $expiration = time() + 2592000;
                $issuer = 'beautyvalet.com';
                $token = Token::create($userId, $secret, $expiration, $issuer);
                $User = self::$UserModel->GetRecordById($userId)->toArray();
				
				Session::put('userID', $userId);
				Session::save();
               
                return response()->json(['success'=>true,'token'=>$token,'details'=>$User],200);
            }else{
				return response()->json(['success'=>false,'message'=>'Internal error'],200);
			}
        }
	}
	public function checkLogin(Request $request){
		//if(!$request->session()->has('userID')){ return response()->json(['success'=>false,'message'=>'Session Expire'],200);}
		return response()->json(['success'=>true,'user_id'=>$GLOBALS['USER.ID']],200);
	}
	public function Logout(Request $request){
		session()->forget('userID');
        $request->session()->flush();
		return response()->json(['success'=>true,'message'=>'Logout successfully'],200);
	}
	public function createAccount(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
			'email' => 'required|email',
            'password' => 'required|min:4',
            'contact' => 'required|min:10',
		],[
            'name.required' => 'Please enter your full name.',
			'email.required' => 'Please enter your email address.',
			'email.email' => 'Please enter valid email address.',
            'password.required' => 'Please enter your password.',
            'contact.required' => 'Please enter contact number.',
            'contact.min' => 'Please enter valid contact number.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('name')){
				return response()->json(['success'=>false, 'message' => $errors->first('name')]);
			}
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
			if($errors->first('contact')){
				return response()->json(['success'=>false, 'message' => $errors->first('contact')]);
			}
			if($errors->first('password')){
				return response()->json(['success'=>false, 'message' => $errors->first('password')]);
			}
		}else{
            if(self::$UserModel->ExistingEmail($request->input('email'))){
                return response()->json(['success'=>false,'message'=>Responses::GetResponse('USER.EMAIL_USED')],200);
            }else if(self::$UserModel->ExistingPhone($request->input('contact'))){
                return response()->json(['success'=>false,'message'=>Responses::GetResponse('USER.PHONE_USED')],200);
            }else{
                $setData['type'] = "User";
                $setData['full_name'] = $request->input('name');
                $setData['email'] = $request->input('email');
                $Password = password_hash($request->post('password'),PASSWORD_BCRYPT);
                $setData['password'] =$Password;
                $setData['contact'] = $request->input('contact');
                $setData['status'] = 1;
                $User = self::$UserModel->CreateRecord($setData);

                return response()->json(['success'=>true, 'message'=>'User register successfully.'],200);

            }
        }
	}
	public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
			'email' => 'required|email'
		],[
			'email.required' => 'Please enter your email address.',
			'email.email' => 'Please enter valid email address.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			return response()->json(['success'=>false, 'message' => $errors]);
		}else{
			$userData = self::$UserModel->where('email',$request->input('email'))->where('status','!=', 3)->first();
			if(isset($userData->id)){
				$temOTP = rand(000000,999999).$userData->id;
				$setData['id'] = $userData->id;
                $setData['otp'] = $temOTP;
                self::$UserModel->UpdateRecord($setData);
				
				return response()->json(['success'=>true,'otp' => $temOTP,'message'=>'Password link send successfully'],200);
				
			}else{
				return response()->json(['success'=>false,'message'=>'This email address is not registered'],200);
			}
            
        }
	}
	public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:4'
		],[
            'password.required' => 'Please enter your password.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			return response()->json(['success'=>false, 'message' => $errors]);
		}else{
			$userData = self::$UserModel->where('otp',$request->input('otp'))->first();
			if(isset($userData->id)){
				$setData['id'] = $userData->id;
				$Password = password_hash($request->post('password'),PASSWORD_BCRYPT);
                $setData['otp'] = NULL;
                self::$UserModel->UpdateRecord($setData);
				return response()->json(['success'=>true,'message'=>'Password updated successfully'],200);
			}else{
				return response()->json(['success'=>false,'message'=>'Invalid url'],200);
			}
		}
	}
	public function getProfile(Request $request){
		$userData = self::$UserModel->where('id',$GLOBALS['USER.ID'])->first();
		return response()->json(['success'=>true,'user_data'=>$userData],200);
	}
	public function updateProfile(Request $request){
		
		$validator = Validator::make($request->all(), [
			'full_name' => 'required',
			'contact' => 'required',
			'address' => 'required',
			'state' => 'required',
			'city' => 'required',
			'zipcode' => 'required',
			'country' => 'required'
			
		],[
			'full_name.required' => 'Please enter full name.',
			'contact.required' => 'Please enter contact number.',
			'address.required' => 'Please enter address.',
			'state.required' => 'Please enter state.',
			'city.required' => 'Please enter city.',
			'zipcode.required' => 'Please enter zipcode.',
			'country.required' => 'Please enter country.'
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('full_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('full_name')]);
			}
			if($errors->first('contact')){
				return response()->json(['success'=>false, 'message' => $errors->first('contact')]);
			}
			if($errors->first('address')){
				return response()->json(['success'=>false, 'message' => $errors->first('address')]);
			}
			if($errors->first('state')){
				return response()->json(['success'=>false, 'message' => $errors->first('state')]);
			}
			if($errors->first('city')){
				return response()->json(['success'=>false, 'message' => $errors->first('city')]);
			}
			if($errors->first('zipcode')){
				return response()->json(['success'=>false, 'message' => $errors->first('zipcode')]);
			}
			if($errors->first('country')){
				return response()->json(['success'=>false, 'message' => $errors->first('country')]);
			}
			
		}else{
				
				$setData['full_name'] = ucwords($request->input('full_name'));
				$setData['contact'] = $request->input('contact');
				$setData['address'] = $request->input('address');
				$setData['state'] = $request->input('state');
				$setData['city'] = $request->input('city');
				$setData['zipcode'] = ucwords($request->input('zipcode'));
				$setData['country'] = ucwords($request->input('country'));
				
				$orderData = self::$UserModel->where('id',$GLOBALS['USER.ID'])->update($setData);
				
				return response()->json(['success'=>true,'message'=>'Profile updated successfully'],200);
				
		}
		
	}
	public function updatePassword(Request $request){
		
		$validator = Validator::make($request->all(), [
			'current_password' => 'required',
			'new_password' => 'required',
			'confirm_password' => 'required',
			
		],[
			'current_password.required' => 'Please enter current password.',
			'new_password.required' => 'Please enter new password.',
			'confirm_password.required' => 'Please enter confirm password.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('current_password')){
				return response()->json(['success'=>false, 'message' => $errors->first('current_password')]);
			}
			if($errors->first('new_password')){
				return response()->json(['success'=>false, 'message' => $errors->first('new_password')]);
			}
			if($errors->first('confirm_password')){
				return response()->json(['success'=>false, 'message' => $errors->first('confirm_password')]);
			}
			
		}else{
			if($request->post('new_password') == $request->post('confirm_password')){
				$Password = password_hash($request->post('new_password'),PASSWORD_BCRYPT);
				$setData['password'] =$Password;
				$orderData = self::$UserModel->where('id',$GLOBALS['USER.ID'])->update($setData);
				
				return response()->json(['success'=>true,'message'=>'Password updated successfully'],200);
			}else{
				return response()->json(['success'=>false,'message'=>'Password do not match'],200);
			}
				
		}
		
	}

}