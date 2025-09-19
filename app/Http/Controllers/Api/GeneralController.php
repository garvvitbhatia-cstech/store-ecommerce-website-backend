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
use App\Models\Newsletters;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\Brands;
use App\Models\Settings;
use App\Models\InnerPages;
use App\Models\Contact;
use App\Models\Category;
use Session;
 
class GeneralController extends Controller{
	
	private static $UserModel;
	private static $Products;
	private static $Carts;
	private static $ProductImages;
	private static $Newsletters;
	private static $Admin;
	private static $Banner;
	private static $Brands;
	private static $Settings;
	private static $InnerPages;
	private static $Contact;
	private static $Category;
	
	public function __construct(){
		self::$UserModel = new User();
		self::$Products = new Products();
		self::$Carts = new Carts();
		self::$ProductImages = new ProductImages();
		self::$Newsletters = new Newsletters();
		self::$Admin = new Admin();
		self::$Banner = new Banner();
		self::$Brands = new Brands();
		self::$Settings = new Settings();
		self::$InnerPages = new InnerPages();
		self::$Contact = new Contact();
		self::$Category = new Category();
	}
	
	public function setNewsletter(Request $request){
        $validator = Validator::make($request->all(), [
			'email' => 'required|email'
		],[
			'email.required' => 'Please enter your email address.',
			'email.email' => 'Please enter valid email address.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
		}else{
			$userData = self::$Newsletters->where('email',$request->input('email'))->where('status','!=', 3)->first();
			if(!isset($userData->id)){
				$setData['name'] = $request->input('name');
				$setData['email'] = $request->input('email');
				self::$Newsletters->create($setData);
				return response()->json(['success'=>true,'message'=>'Newsletter saved successfully'],200);
				
			}else{
				return response()->json(['success'=>true,'message'=>'Newsletter saved successfully'],200);
			}
            
        }
    }
	public function saveContact(Request $request){
        $validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required',
			'message' => 'required',
		],[
			'name.required' => 'Please enter your name.',
			'email.required' => 'Please enter your email address.',
			'email.email' => 'Please enter valid email address.',
			'phone.required' => 'Please enter your phone number.',
			'message.required' => 'Please enter your message.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('name')){
				return response()->json(['success'=>false, 'message' => $errors->first('name')]);
			}
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
			if($errors->first('phone')){
				return response()->json(['success'=>false, 'message' => $errors->first('phone')]);
			}
			if($errors->first('message')){
				return response()->json(['success'=>false, 'message' => $errors->first('message')]);
			}
		}else{
			$setData['name'] = $request->input('name');
			$setData['email'] = $request->input('email');
			$setData['subject'] = $request->input('subject');
			$setData['message'] = $request->input('message');
			$setData['phone'] = $request->input('phone');
			$setData['type'] = $request->input('type');
			if($request->input('product_id') != ""){
				$setData['product_id'] = implode(',',$request->input('product_id'));
			}
			self::$Contact->create($setData);
			return response()->json(['success'=>true,'message'=>'Enquiry sent successfully'],200);
            
        }
    }
	public function getCms(Request $request){
		$pageData = self::$InnerPages->where('id',$request->input('id'))->first();
		return response()->json(['success'=>true,'data'=>$pageData],200);
	}
	public function getProfile(Request $request){
		$userData = self::$Admin->where('id',$request->input('id'))->first();
		return response()->json(['success'=>true,'data'=>$userData],200);
	}
	public function getSetting(Request $request){
		$userData = self::$Settings->where('id',1)->first();
		return response()->json(['success'=>true,'data'=>$userData],200);
	}
	public function getBanners(Request $request){
		$banners = self::$Banner->where('status',1)->orderBy('id','DESC')->get()->take(4);
		foreach($banners as $key => $banner){
			$banner->banner = env('SITE_URL').'public/assets/images/admin/banners/'.$banner->banner;
		}
		return response()->json(['success'=>true,'banners'=>$banners],200);
	}
	public function getBrands(Request $request){
		$brands = self::$Brands->where('status',1)->orderBy('title','ASC')->get();
		foreach($brands as $key => $brand){
			$brand->banner = env('SITE_URL').'public/assets/images/admin/gallery/'.$brand->banner;
		}
		return response()->json(['success'=>true,'brands'=>$brands],200);
	}
	public function getBrandsByCategory(Request $request){
		$CatData = self::$Category->select('id')->where('slug',$request->cat)->first();
		
		$brands = self::$Products->select('brand_id')->where('category_id',$CatData->id)->where('status',1)->groupBy('brand_id')->get();
		$brandData = [];
		foreach($brands as $key => $brand){
			$bData = self::$Brands->where('id',$brand->brand_id)->first();
			$brandData[$key]['id'] = $bData->id;
			$brandData[$key]['title'] = $bData->title;
			$brandData[$key]['slug'] = $bData->slug;
		}
		return response()->json(['success'=>true,'brands'=>$brandData],200);
	}
	public function getCapacityByCategory(Request $request){
		$CatData = self::$Category->select('id')->where('slug',$request->cat)->first();
		
		$capacities = self::$Products->select('capacity')->where('category_id',$CatData->id)->where('status',1)->where('capacity','!=','')->groupBy('capacity')->get();
		return response()->json(['success'=>true,'capacities'=>$capacities],200);
	}
	

}