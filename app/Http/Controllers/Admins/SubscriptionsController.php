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
use App\Models\Subscriptions;
use App\Models\CouponCode;
 
class SubscriptionsController extends Controller{

	public function subscriptions(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('title') != ''){
			$cond['title'] = array('title', 'like', '%'.$request->input('title').'%');
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = Subscriptions::where($conditions)->orderBy('id','desc')->paginate(PAGE_LIMIT);	
		return view('admins.subscriptions.subscriptions',compact('pages'));
	}

	public function add_subscription(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:subscriptions',
				'price' => 'required|numeric',
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
				'price.required' => 'Please enter price',
				'price.numeric' => 'Please enter valid price',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$subscription = new Subscriptions;			
				$subscription->title = $request->title;
				$subscription->price = $request->price;
				$subscription->status = $status;			
				$subscription->save();
	
				return redirect('admins/subscriptions')->with('success','Subscription has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		return view('admins.subscriptions.add_subscription');	
	}
	
	public function edit_subscription(Request $request, $id){
		$subscription = Subscriptions::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:subscriptions,title,'.$subscription->id,
				'price' => 'required|numeric',
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
				'price.required' => 'Please enter price',
				'price.numeric' => 'Please enter valid price',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}			
							
				$subscription = Subscriptions::find($subscription->id);
				$subscription->title = $request->title;
				$subscription->price = $request->price;
				$subscription->status = $status;
				$subscription->save();
				return back()->with('success','Subscription has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($subscription)){
			return view('admins.subscriptions.edit_subscription',compact('subscription'));
		}else{
			return redirect('admins/subscriptions');
		}
		
	}
	
	public function couponCode(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('title') != ''){
			$cond['coupon'] = array('coupon', 'like', '%'.$request->input('title').'%');
		}
		if($request->input('type') != ''){
			$cond['type'] = array('type', $request->input('type'));
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = CouponCode::where($conditions)->orderBy('id','desc')->paginate(PAGE_LIMIT);	
		return view('admins.subscriptions.coupon_code',compact('pages'));
	}

	public function addCouponCode(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'coupon' => 'required|unique:coupon_code',
				'type' => 'required',
				'value' => 'required|numeric',
				'start_date' => 'required',
				'expired' => 'required',
			],[
				'coupon.required' => 'Please enter title',
				'coupon.unique' => 'Couponcode already exists',
				'type.required' => 'Please select type',
				'value.required' => 'Please enter price',
				'value.numeric' => 'Please enter valid price',
				'start_date.required' => 'Please enter start date',
				'expired.required' => 'Please enter end date',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$couponcode = new CouponCode;			
				$couponcode->coupon = strtoupper(trim($request->coupon));
				$couponcode->type = $request->type;
				if(trim($request->value) == ''){ $request->value = 0; }
				$couponcode->value = $request->value;
				$couponcode->start_date = $request->start_date;
				$couponcode->start_date_str = strtotime($request->start_date);
				$couponcode->expired = $request->expired;				
				$couponcode->expire_date_str = strtotime($request->expired);				
				$couponcode->status = $status;
				$couponcode->save();
	
				return redirect('admins/coupon-code')->with('success','Couponcode has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		return view('admins.subscriptions.add_coupon_code');	
	}
	
	public function editCouponCode(Request $request, $id){
		$couponcode = CouponCode::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'coupon' => 'required|unique:coupon_code,coupon,'.$couponcode->id,
				'type' => 'required',
				'value' => 'required|numeric',
				'start_date' => 'required',
				'expired' => 'required',
			],[
				'coupon.required' => 'Please enter title',
				'coupon.unique' => 'Couponcode already exists',
				'type.required' => 'Please select type',
				'value.required' => 'Please enter price',
				'value.numeric' => 'Please enter valid price',
				'start_date.required' => 'Please enter start date',
				'expired.required' => 'Please enter end date',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}			
							
				$couponcode = CouponCode::find($couponcode->id);
				$couponcode->coupon = strtoupper(trim($request->coupon));
				$couponcode->type = $request->type;
				if(trim($request->value) == ''){ $request->value = 0; }
				$couponcode->value = $request->value;
				$couponcode->start_date = $request->start_date;
				$couponcode->start_date_str = strtotime($request->start_date);
				$couponcode->expired = $request->expired;				
				$couponcode->expire_date_str = strtotime($request->expired);				
				$couponcode->status = $status;		
				$couponcode->save();
				return back()->with('success','Subscription has been updated successfully');		
			}
			catch(\Exception $e){
				//pr($e->getMessage());die;
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($couponcode)){
			return view('admins.subscriptions.edit_coupon_code',compact('couponcode'));
		}else{
			return redirect('admins/coupon_code');
		}
		
	}

}