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
use App\Models\Discounts;
 
class CartController extends Controller{
	
	private static $UserModel;
	private static $Products;
	private static $Carts;
	private static $ProductImages;
	private static $Discounts;
	
	public function __construct(){
		self::$UserModel = new User();
		self::$Products = new Products();
		self::$Carts = new Carts();
		self::$ProductImages = new ProductImages();
		self::$Discounts = new Discounts();
	}

	public function setCart(Request $request){
		
		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
			'quantity' => 'required',
			
		],[
			'product_id.required' => 'Please enter product_id.',
			'quantity.required' => 'Please enter quantity.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('product_id')){
				return response()->json(['success'=>false, 'message' => $errors->first('product_id')]);
			}
			if($errors->first('quantity')){
				return response()->json(['success'=>false, 'message' => $errors->first('quantity')]);
			}
		}else{
			$productData = self::$Products->select('id','price','discounted_price','saling_price')->where('id',$request->input('product_id'))->first();
			$productPrice = 0;
			if($productData->discounted_price != "" && $productData->discounted_price > 0){
				$productPrice = $productData->discounted_price;
			}else{
				$productPrice = $productData->saling_price;
			}
			$item = self::$Carts->where('product_id',$request->input('product_id'))->where('user_id',$request->input('temp_id'))->first();
			if(isset($item->id)){
				$newQty = ($item->quantity+$request->input('quantity'));
				$total = $productPrice*$newQty;
				$setData['quantity'] = $newQty;
				$setData['price'] = $productPrice;
				$setData['total'] = $total;
				self::$Carts->where('id',$item->id)->update($setData);
			}else{
				$total = $productPrice*$request->input('quantity');
				$setData['user_id'] = $request->input('temp_id');
				$setData['product_id'] = $request->input('product_id');
				$setData['quantity'] = $request->input('quantity');
				$setData['price'] = $productPrice;
				$setData['total'] = $total;
				self::$Carts->create($setData);
			}
		}
		return response()->json(['success'=>true,'message'=>'Product added successfully'],200);
	}
	public function removeCart(Request $request){
		
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
			
			$item = self::$Carts->where('id',$request->input('id'))->first();
			if(isset($item->id)){
				self::$Carts->where('id',$item->id)->delete();
			}else{
				return response()->json(['success'=>false,'message'=>'id does not exist'],200);
			}
		}
		return response()->json(['success'=>true,'message'=>'Cart deleted successfully'],200);
	}
	public function updateCart(Request $request){
		$item = self::$Carts->where('id',$request->input('id'))->first();
		$qty = $request->input('qty');
		$total = $qty*$item->price;
		$setData['quantity'] = $qty;
		$setData['total'] = $total;
		self::$Carts->where('id',$item->id)->update($setData);
		return response()->json(['success'=>true,'message'=>'Cart updated successfully'],200);
	}
	public function countCart(Request $request){
		$count = self::$Carts->where('user_id',$request->input('temp_id'))->count();
		return response()->json(['success'=>true,'cart_count'=>$count],200);
	}
	public function getCartList(Request $request){
		$items = self::$Carts->join('products','products.id','=','carts.product_id')->select('carts.*','products.product_name','products.price','products.discounted_price','products.saling_price','products.product_name','products.product_name')->where('carts.user_id',$request->input('temp_id'))->get();
		$subTotal = 0;
		foreach($items as $key => $item){
			$p_image = '';
			$image = self::$ProductImages->where('product_id',$item->product_id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			$item->product_image = $p_image;
			$item->price = number_format($item->price, 2, '.', '');
			$item->discounted_price = number_format($item->discounted_price, 2, '.', '');
			$item->discount_apply = $item->saling_price > 0 ? true : false;
			$item->list_price = $item->discounted_price > 0 ? number_format($item->discounted_price, 2, '.', '') : number_format($item->saling_price, 2, '.', '');
			$item->title = strlen($item->product_name) > 20 ? substr($item->product_name,0,20).'...' : $item->product_name;
			$item->total_price = number_format($item->list_price*$item->quantity, 2, '.', '');
			
			$subTotal = $subTotal+$item->total;
		}
		$discountText = '';
		$discountAmt = 0;
		$discount_id = 0;
		$discount = self::$Discounts->where('user_id',$request->input('temp_id'))->first();
		if(isset($discount->id)){
			$discountAmt = $discount->discount_amt;
			$sign = $discount->type == 'percentage' ? '%' : 'INR';
			$discountText = $discount->coupon.' applied successfully. '.$discount->discount.' '.$sign.' OFF';
			$discount_id = $discount->id;
		}
		
		$shipping = 0;
		$total = ($subTotal+$shipping)-$discountAmt;
		$subTotal = number_format($subTotal, 2, '.', '');
		$discountAmt = number_format($discountAmt, 2, '.', '');
		$total = number_format($total, 2, '.', '');
		$shipping = number_format($shipping, 2, '.', '');
		return response()->json(['success'=>true,'items'=>$items,'sub_total' =>$subTotal,'total'=> $total,'shipping' => $shipping,'discount' => $discountAmt,'discount_text' => $discountText,'discount_id' => $discount_id],200);
	}

}