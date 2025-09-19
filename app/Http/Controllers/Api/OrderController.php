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
use App\Models\Products;
use App\Models\Carts;
use App\Models\ProductImages;
use App\Models\Responses;
use App\Models\Orders;
use App\Models\ProductOrder;
use App\Models\CouponCode;
use App\Models\Discounts;
 
class OrderController extends Controller{
	
	private static $UserModel;
	private static $Products;
	private static $Carts;
	private static $ProductImages;
	private static $Orders;
	private static $ProductOrder;
	private static $CouponCode;
	private static $Discounts;
	
	public function __construct(){
		self::$UserModel = new User();
		self::$Products = new Products();
		self::$Carts = new Carts();
		self::$ProductImages = new ProductImages();
		self::$Orders = new Orders();
		self::$ProductOrder = new ProductOrder();
		self::$CouponCode = new CouponCode();
		self::$Discounts = new Discounts();
	}
	public function applyCoupon(Request $request){
		
		$validator = Validator::make($request->all(), [
			'coupon_code' => 'required',
			
		],[
			'coupon_code.required' => 'Please enter coupon code.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('coupon_code')){
				return response()->json(['success'=>false, 'message' => $errors->first('coupon_code')]);
			}
		}else{
			$currentDate = time();
			$couponCodeData = self::$CouponCode->where('status',1)->where('coupon',$request->input('coupon_code'))->first();
			if(isset($couponCodeData->id)){
				if($currentDate >= $couponCodeData->start_date_str && $currentDate <= $couponCodeData->expire_date_str){
					
					$items = self::$Carts->where('carts.user_id',$request->input('temp_id'))->get();
					$subTotal = 0;
					foreach($items as $key => $item){
						$subTotal = $subTotal+$item->total;
					}
					if($couponCodeData->type == 'amount'){
						$discountAmt = $couponCodeData->value;
					}else{
						$discountAmt = ($subTotal*$couponCodeData->value)/100;
					}
					
					$discount = self::$Discounts->where('user_id',$request->input('temp_id'))->where('coupon',$request->input('coupon_code'))->first();
					if(!isset($discount->id)){
						$setData['user_id'] = $request->input('temp_id');
						$setData['coupon'] = $request->input('coupon_code');
						$setData['discount_amt'] = $discountAmt;
						$setData['discount'] = $couponCodeData->value;
						$setData['type'] = $couponCodeData->type;
						self::$Discounts->create($setData);
					}
					
					return response()->json(['success'=>true,'message' =>'Coupon applied successfully.','discountAmt' =>$discountAmt],200);
				}else{
					return response()->json(['success'=>false, 'message' => 'Coupon code expired']);
				}
			}else{
				return response()->json(['success'=>false, 'message' => 'Invalid coupon code']);
			}
		}
		
		
	}
	public function removeCoupon(Request $request){
		
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			
		],[
			'id.required' => 'Please enter id.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('id')){
				return response()->json(['success'=>false, 'message' => $errors->first('id')]);
			}
		}else{
			
			$item = self::$Discounts->where('id',$request->input('id'))->first();
			if(isset($item->id)){
				self::$Discounts->where('id',$item->id)->delete();
			}else{
				return response()->json(['success'=>false,'message'=>'id does not exist'],200);
			}
		}
		return response()->json(['success'=>true,'message'=>'Coupon deleted successfully'],200);
	}
	public function getOrder(Request $request){
		$order = self::$Orders->where('id',$request->input('order_id'))->first();
		
		if($order->status == 1){
			$status = 'Pending';
		}
		if($order->status == 2){
			$status = 'Shipped';
		}
		if($order->status == 3){
			$status = 'On The Way';
		}
		if($order->status == 3){
			$status = 'Delivered';
		}
		$order->order_status = $status;
		$order->order_date = date('d F Y',strtotime($order->created_at));
		
		$items = self::$ProductOrder->where('order_id',$request->input('order_id'))->get();
		
		
		return response()->json(['success'=>true,'order' =>$order,'items' => $items],200);
	}
	public function getOrderList(Request $request){
		$orders = self::$Orders->where('user_id',$GLOBALS['USER.ID'])->orderBy('id','DESC')->get();
		foreach($orders as $key=> $order){
			if($order->status == 1){
				$status = 'Pending';
			}
			if($order->status == 2){
				$status = 'Shipped';
			}
			if($order->status == 3){
				$status = 'On The Way';
			}
			if($order->status == 3){
				$status = 'Delivered';
			}
			$order->order_status = $status;
			$order->order_date = date('d F Y',strtotime($order->created_at));
		}
		return response()->json(['success'=>true,'orders' =>$orders],200);
	}
	public function placeOrder(Request $request){
		
		$validator = Validator::make($request->all(), [
			'b_first_name' => 'required',
			'b_last_name' => 'required',
			'b_email' => 'required',
			'b_mobile' => 'required',
			'b_address' => 'required',
			'b_country' => 'required',
			'b_state' => 'required',
			'b_city' => 'required',
			'b_zipcode' => 'required',
			's_first_name' => 'required',
			's_last_name' => 'required',
			's_email' => 'required',
			's_mobile' => 'required',
			's_address' => 'required',
			's_country' => 'required',
			's_state' => 'required',
			's_city' => 'required',
			's_zipcode' => 'required',
			
		],[
			'b_first_name.required' => 'Please enter billing first name.',
			'b_last_name.required' => 'Please enter billing last name.',
			'b_email.required' => 'Please enter billing email address.',
			'b_mobile.required' => 'Please enter billing mobile.',
			'b_address.required' => 'Please enter billing address.',
			'b_country.required' => 'Please enter billing country.',
			'b_state.required' => 'Please enter billing state.',
			'b_city.required' => 'Please enter billing city.',
			'b_zipcode.required' => 'Please enter billing zipcode.',
			's_first_name.required' => 'Please enter shipping first name.',
			's_last_name.required' => 'Please enter shipping last name.',
			's_email.required' => 'Please enter shipping email address.',
			's_mobile.required' => 'Please enter shipping mobile.',
			's_address.required' => 'Please enter shipping address.',
			's_country.required' => 'Please enter shipping country.',
			's_state.required' => 'Please enter shipping state.',
			's_city.required' => 'Please enter shipping city.',
			's_zipcode.required' => 'Please enter shipping zipcode.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('b_first_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_first_name')]);
			}
			if($errors->first('b_last_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_last_name')]);
			}
			if($errors->first('b_email')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_email')]);
			}
			if($errors->first('b_mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_mobile')]);
			}
			if($errors->first('b_address')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_address')]);
			}
			if($errors->first('b_country')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_country')]);
			}
			if($errors->first('b_state')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_state')]);
			}
			if($errors->first('b_city')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_city')]);
			}
			if($errors->first('b_zipcode')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_zipcode')]);
			}
			if($errors->first('s_first_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_first_name')]);
			}
			if($errors->first('s_last_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_last_name')]);
			}
			if($errors->first('s_email')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_email')]);
			}
			if($errors->first('s_mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_mobile')]);
			}
			if($errors->first('s_address')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_address')]);
			}
			if($errors->first('s_country')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_country')]);
			}
			if($errors->first('s_state')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_state')]);
			}
			if($errors->first('s_city')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_city')]);
			}
			if($errors->first('s_zipcode')){
				return response()->json(['success'=>false, 'message' => $errors->first('s_zipcode')]);
			}
		}else{
				
				$userEmailExist = self::$UserModel->where('email',$request->input('b_email'))->first();
				if(isset($userEmailExist->id)){
					$userID = $userEmailExist->id;
				}else{
					$userPhoneExist = self::$UserModel->where('contact',$request->input('b_mobile'))->first();
					if(isset($userPhoneExist->id)){
						$userID = $userPhoneExist->id;
					}else{
						$setData['type'] = "User";
						$setData['full_name'] = ucwords($request->input('b_first_name').' '.$request->input('b_last_name'));
						$setData['email'] = strtolower($request->input('b_email'));
						$Password = password_hash('12345',PASSWORD_BCRYPT);
						$setData['password'] =$Password;
						$setData['contact'] = $request->input('b_mobile');
						$setData['status'] = 1;
						$User = self::$UserModel->CreateRecord($setData);
						$userID = $User->id;
					}
				}
				
				$items = self::$Carts->join('products','products.id','=','carts.product_id')->select('carts.*','products.product_name')->where('carts.user_id',$request->input('temp_id'))->get();
				$subTotal = 0;
				$shipping = 0;
				foreach($items as $key => $item){
					$subTotal = $subTotal+$item->total;
				}
				
				$discountText = '';
				$discountAmt = 0;
				$discount = self::$Discounts->where('user_id',$request->input('temp_id'))->first();
				if(isset($discount->id)){
					$discountAmt = $discount->discount_amt;
					$discountText = $discount->coupon;
					self::$Discounts->where('id',$discount->id)->delete();
				}
				
				$total = ($subTotal+$shipping)-$discountAmt;
				
				$setData['user_id'] = $userID;
				
				$setData['customer_name'] = ucwords($request->input('b_first_name').' '.$request->input('b_last_name'));
				$setData['customer_email'] = $request->input('b_email');
				$setData['customer_contact'] = $request->input('b_mobile');
				$setData['customer_address'] = $request->input('b_address');
				$setData['customer_pincode'] = $request->input('b_zipcode');
				$setData['customer_state'] = ucwords($request->input('b_state'));
				$setData['customer_city'] = ucwords($request->input('b_city'));
				$setData['customer_country'] = ucwords($request->input('b_country'));
				$setData['shipping_name'] = ucwords($request->input('s_first_name').' '.$request->input('s_last_name'));
				$setData['shipping_email'] = $request->input('s_email');
				$setData['shipping_contact'] = $request->input('s_mobile');
				$setData['shipping_address'] = $request->input('s_address');
				$setData['shipping_country'] = ucwords($request->input('s_country'));
				$setData['shipping_state'] = ucwords($request->input('s_state'));
				$setData['shipping_city'] = ucwords($request->input('s_city'));
				$setData['shipping_zipcode'] = $request->input('s_zipcode');
				$setData['customer_latitude'] = NULL;
				$setData['customer_longitude'] = NULL;
				$setData['notes'] = NULL;
				
				$setData['coupon_code'] = $discountText;
				$setData['price'] = $subTotal;
				$setData['discount'] = $discountAmt;
				$setData['shipping'] = $shipping;
				$setData['total'] = $total;
				
				$setData['payment_through'] = 'Application';
				$setData['payment_method'] = $request->input('payment_method');
				$setData['order_day'] = date('d');
				$setData['order_month'] = date('m');
				$setData['order_year'] = date('Y');
				
				$orderData = self::$Orders->create($setData);
				#update order id
				$updateData['invoice_id'] = rand(1001,9999).$orderData->id;
				self::$Orders->where('id',$orderData->id)->update($updateData);
				
				foreach($items as $key => $item){
					$setData2['order_id'] = $orderData->id;
					$setData2['user_id'] = $userID;
					$setData2['product_id'] = $item->product_id;
					$setData2['product_name'] = $item->product_name;
					$setData2['quantity'] = $item->quantity;
					$setData2['price'] = $item->price;
					$setData2['total'] = $item->total;
					self::$ProductOrder->create($setData2);
				}
				
				//self::$Carts->where('user_id',$request->input('temp_id'))->delete();
				
				return response()->json(['success'=>true,'message'=>'Order created successfully','order_id' => $orderData->id],200);
				
		}
		
	}
	
}