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
use App\Models\Responses;
use App\Models\ProductImages;
use App\Models\Category;
use App\Models\Brands;
use App\Models\Wishlists;
 
class ProductController extends Controller{
	
	private static $UserModel;
	private static $Products;
	private static $ProductImages;
	private static $Category;
	private static $Brands;
	private static $Wishlists;
	
	public function __construct(){
		self::$UserModel = new User();
		self::$Products = new Products();
		self::$ProductImages = new ProductImages();
		self::$Category = new Category();
		self::$Brands = new Brands();
		self::$Wishlists = new Wishlists();
	}
	public function wishlistDelete(Request $request){
		self::$Wishlists->where('id',$request->id)->delete();
		return response()->json(['success'=>true,'message' => 'Item deleted successfully.'],200); 
	}
	public function wishlistList(Request $request){
		$items = self::$Wishlists->where('user_id',$GLOBALS['USER.ID'])->where('status',1)->orderBy('id','DESC')->get();
		foreach($items as $key => $item){
			$product = self::$Products->where('id',$item->product_id)->first();
			
			$p_image = '';
			$image = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			$item->product_image = $p_image;
			$item->price = number_format($product->price, 2, '.', '');
			$item->discounted_price = number_format($product->discounted_price, 2, '.', '');
			$item->discount_apply = $product->saling_price > 0 ? true : false;
			$item->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price, 2, '.', '') : number_format($product->saling_price, 2, '.', '');
			$item->product_name = strlen($product->product_name) > 20 ? substr($product->product_name,0,20).'...' : $product->product_name;
			
		}
		return response()->json(['success'=>true,'items' => $items],200); 
	}
	public function addToWishlist(Request $request){
		if($request->t == 'A'){
			$wishlist = self::$Wishlists->where('product_id',$request->p)->where('user_id',$request->token)->first();
			if(isset($wishlist->id)){
				self::$Wishlists->where('product_id',$request->p)->where('user_id',$request->token)->update(['status' => 1]);
			}else{
				$setData['product_id'] =$request->p;
				$setData['user_id'] = $request->token;
				$setData['status'] = 1;
				$User = self::$Wishlists->CreateRecord($setData);
			}
			$count = 1;
		}else{
			self::$Wishlists->where('product_id',$request->p)->where('user_id',$request->token)->update(['status' => 2]);
			$count = 0;
		}
		return response()->json(['success'=>true,'message' => 'Wishlist updated successfully.','count' => $count],200); 
	}
	public function getCategories(Request $request){
		
		$categoryQuery = self::$Category->where('status',1)->where('parent_id',0);
		if($request->input('is_header_menu')){
			$categoryQuery = $categoryQuery->where('is_header_menu',1);
		}
		$categories = $categoryQuery->get()->take(20);
		
		foreach($categories as $key => $category){
			$noOfProducts = self::$Products->where('category_id',$category->id)->where('status',1)->count();
			$p_image = '';
			if(isset($category->category_banner)){
				$p_image = env('SITE_URL').'public/assets/images/admin/categories/'.$category->category_banner;
			}
			$category->banner = $p_image;
			$category->no_of_products = $noOfProducts;
			
			$pwCount = self::$Products->where('category_id',$category->id)->where('is_wholesale',1)->where('status',1)->count();
			$category->pw_count = $pwCount; 
			$subCategories = self::$Category->where('status',1)->where('parent_id',$category->id)->get();
			$category->sub_categories = $subCategories;
			foreach($subCategories as $key => $subCategory){
				$subCategory->sub_sub_categories = self::$Category->where('status',1)->where('parent_id',$subCategory->id)->get();
			}
			
		}
		return response()->json(['success'=>true,'categories'=>$categories],200);
	}
	public function getWholeProducts(Request $request){
		
		$productQuery = self::$Products->select('id','product_name','price','discounted_price','slug','minimum_order_qty','saling_price')->where('is_wholesale',1)->where('status',1);
		
		if(is_array($request->input('category_id')) && count($request->input('category_id')) > 0){
			$productQuery = $productQuery->whereIn('category_id', $request->input('category_id'));
		}
		if($request->input('product_name') && $request->input('product_name') != ""){
			$productQuery = $productQuery->where('product_name', 'like', '%'.$request->input('product_name').'%');
		}
		
		$products = $productQuery->orderBy('id','DESC')->paginate(16);
		
		foreach($products as $key => $product){
			$p_image = env('SITE_URL').'public/assets/images/no-img.jpg';
			$image = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			$product->product_image = $p_image;
			$product->price = number_format($product->price, 2, '.', '');
			$product->discounted_price = number_format($product->discounted_price, 2, '.', '');
			$product->discount_apply = $product->saling_price > 0 ? true : false;
			$product->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price, 2, '.', '') : number_format($product->saling_price, 2, '.', '');
			$product->title = strlen($product->product_name) > 20 ? substr($product->product_name,0,20).'...' : $product->product_name;
		}
		return response()->json(['success'=>true,'products'=>$products],200);
	}
	public function getSubcategories(Request $request){
		$categoryData = self::$Category->where('slug',$request->input('slug'))->first();
		$subCategories = self::$Category->select('id','title','slug')->where('parent_id',$categoryData->id)->get();
		foreach($subCategories as $key => $subCategory){
			$pCount = self::$Products->where('sub_category_id',$subCategory->id)->where('status',1)->count();
			$subCategory->count = $pCount; 
		}
		return response()->json(['success'=>true,'category_data' => $categoryData, 'sub_categories' => $subCategories],200);
	}
	public function getBrandProducts(Request $request){
		$brandData = self::$Brands->where('slug',$request->input('slug'))->first();
		$productQuery = self::$Products->select('id','product_name','price','discounted_price','slug','sub_category_id','category_id','saling_price')->where('brand_id',$brandData->id)->where('status',1);
		
		if($request->input('product_name') && $request->input('product_name') != ""){
			$productQuery = $productQuery->where('product_name', 'like', '%'.$request->input('product_name').'%');
		}
		$products = $productQuery->orderBy('id','DESC')->paginate(18);
		
		foreach($products as $key => $product){
			$p_image = env('SITE_URL').'public/assets/images/no-img.jpg';
			$image = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			
			$discountPercentage = 0;
			if($product->price > 0 && $product->discounted_price > 0){
				$priceDiff = $product->price-$product->discounted_price;
				$discountPercentage = ($priceDiff/$product->price)*100;
			}
			
			$product->discount_percentage = ceil($discountPercentage);
			
			
			$product->rating = rand(1,5);
			$product->no_of_reviews = number_format(rand(1001,9999));
			
			$product->product_image = $p_image;
			$product->price = number_format($product->price);
			$product->discounted_price = $product->discounted_price;
			$product->discount_apply = $product->saling_price > 0 ? true : false;
			$product->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price) : number_format($product->saling_price);
			$product->title = strlen($product->product_name) > 20 ? substr($product->product_name,0,20).'...' : $product->product_name;
		}
		return response()->json(['success'=>true,'category_data' => $brandData, 'products'=>$products],200);
	}
	public function getCategoryProducts(Request $request){
	
		$slug = $request->input('slug');
		$slug2 = $request->input('slug2');
		$finalSlug = $request->input('slug2');
		$slug3 = '';
		if($request->input('slug3') && $request->input('slug3') != ""){
			$slug3 = $request->input('slug3');
			$finalSlug = $request->input('slug3');
		}
		
		$categoryData = self::$Category->where('slug',$finalSlug)->first();
		
		$productQuery = self::$Products->select('id','product_name','price','discounted_price','slug','sub_category_id','category_id','saling_price');
		
		if($request->input('slug3') && $request->input('slug3') != ""){
			$productQuery->where('sub_sub_category_id',$categoryData->id);
		}else{
			$productQuery->where('sub_category_id',$categoryData->id);
		}
		$productQuery->where('status',1);
		
		if(is_array($request->input('sub_cat_id')) && count($request->input('sub_cat_id')) > 0){
			$productQuery = $productQuery->whereIn('sub_category_id', $request->input('sub_cat_id'));
		}
		
		if(is_array($request->input('brand_id')) && count($request->input('brand_id')) > 0){
			$productQuery = $productQuery->whereIn('brand_id', $request->input('brand_id'));
		}
		if(is_array($request->input('capacity_id')) && count($request->input('capacity_id')) > 0){
			$productQuery = $productQuery->whereIn('capacity', $request->input('capacity_id'));
		}
		if(is_array($request->input('rating')) && count($request->input('rating')) > 0){
			$productQuery = $productQuery->whereIn('rating', $request->input('rating'));
		}
		
		if($request->input('product_name') && $request->input('product_name') != ""){
			$productQuery = $productQuery->where('product_name', 'like', '%'.$request->input('product_name').'%');
		}
		
		$minDiscount = $maxDiscount = 0;
		$discountArray = [];
		foreach($request->input('discount') as $key => $discount){
			$explode2 = explode('_',$discount);
			$discountArray[] = $explode2[0];
			$discountArray[] = $explode2[1];
		}
		if(count($discountArray) > 0){
			$discountArray = array_unique($discountArray);
			$minDiscount = min($discountArray);
			$maxDiscount = max($discountArray);
		}
		
		if($minDiscount > 0 && $maxDiscount > 0){
			$productQuery = $productQuery->where('offer_value','>=',$minDiscount)->where('offer_value','<=',$maxDiscount)->where('offer_type','Percent');
		}
		

		$minPrice = $maxPrice = 0;
		$prices = [];
		foreach($request->input('price_array') as $key => $priceRange){
			$explode = explode('_',$priceRange);
			$prices[] = $explode[0];
			$prices[] = $explode[1];
		}
		if(count($prices) > 0){
			$prices = array_unique($prices);
			$minPrice = min($prices);
			$maxPrice = max($prices);
		}
		
		if($minPrice > 0 && $maxPrice > 0){
			$productQuery = $productQuery->where('saling_price','>=',$minPrice)->where('saling_price','<=',$maxPrice);
		}
		
		$products = $productQuery->orderBy('id','DESC')->paginate(16);
		
		foreach($products as $key => $product){
			
			$isWishlist = 0;
			if($request->token && $request->token > 0){
				$wishlist = self::$Wishlists->where('product_id',$product->id)->where('user_id',$request->token)->where('status',1)->count();
				$isWishlist = $wishlist;
			}
			
			$p_image = env('SITE_URL').'public/assets/images/no-img.jpg';
			$image = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			
			$discountPercentage = 0;
			if($product->price > 0 && $product->discounted_price > 0){
				$priceDiff = $product->price-$product->discounted_price;
				$discountPercentage = ($priceDiff/$product->price)*100;
			}
			
			$product->discount_percentage = ceil($discountPercentage);
			
			$product->is_wishlist = $isWishlist;
			$product->rating = rand(1,5);
			$product->no_of_reviews = number_format(rand(1001,9999));
			$product->product_image = $p_image;
			$product->price = number_format($product->price);
			$product->discounted_price = $product->discounted_price;
			$product->discount_apply = $product->saling_price > 0 ? true : false;
			$product->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price) : number_format($product->saling_price, 2);
			$product->title = strlen($product->product_name) > 20 ? substr($product->product_name,0,20).'...' : $product->product_name;
		}
		$slug = str_replace("-"," ",$slug);
		$slug2 = str_replace("-"," ",$slug2);
		$breadcrumb = ucwords($slug).' >> '.ucwords($slug2);
		if($request->input('slug3') && $request->input('slug3') != ""){
			$slug3 = str_replace("-"," ",$slug3);
			$breadcrumb = $breadcrumb.' >> '.ucwords($slug3);
		}
		
		return response()->json(['success'=>true,'breadcrumb' => $breadcrumb, 'category_data' => $categoryData, 'products'=>$products],200);
	}
	public function getFeatureProducts(Request $request){
		//$products = self::$Products->select('id','product_name','price','discounted_price','slug')->where('status',1)->inRandomOrder()->get()->take(24);
		$products = self::$Products->select('id','product_name','price','discounted_price','slug','saling_price')->where('status',1)->get()->take(24);
		foreach($products as $key => $product){
			
			$isWishlist = 0;
			if($request->token && $request->token > 0){
				$wishlist = self::$Wishlists->where('product_id',$product->id)->where('user_id',$request->token)->where('status',1)->count();
				$isWishlist = $wishlist;
			}
			
			$p_image = env('SITE_URL').'public/assets/images/no-img.jpg';
			$image = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->first();
			if(isset($image->id)){
				$p_image = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			
			$discountPercentage = 0;
			if($product->price > 0 && $product->saling_price > 0){
				$priceDiff = $product->price-$product->saling_price;
				$discountPercentage = ($priceDiff/$product->price)*100;
			}
			
			$product->discount_percentage = ceil($discountPercentage);
			
			
			$product->rating = rand(1,5);
			$product->no_of_reviews = number_format(rand(1001,9999));
			$product->product_image = $p_image;
			$product->is_wishlist = $isWishlist;
			$product->price = number_format($product->price);
			$product->discounted_price = $product->discounted_price;
			$product->discount_apply = $product->saling_price > 0 ? true : false;
			$product->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price) : number_format($product->saling_price);
			$product->title = strlen($product->product_name) > 20 ? substr($product->product_name,0,20).'...' : $product->product_name;
		}
		return response()->json(['success'=>true,'products'=>$products],200);
	}
	public function getProduct(Request $request){
		$validator = Validator::make($request->all(), [
			'slug' => 'required',
		],[
			'slug.required' => 'Please enter slug.',
		]);
		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('slug')){
				return response()->json(['success'=>false, 'message' => $errors->first('slug')]);
			}
		}else{
			$product = self::$Products->where('slug',$request->input('slug'))->first();
			
			$slug1 = $slug2 = $slug3 = '';
			if($product->category_id > 0){
				$category1 = self::$Category->select('title')->where('id',$product->category_id)->first();
				$slug1 = ucwords($category1->title);
			}
			if($product->sub_category_id > 0){
				$category2 = self::$Category->select('title')->where('id',$product->sub_category_id)->first();
				$slug2 = ucwords($category2->title);
			}
			if($product->sub_sub_category_id > 0){
				$category3 = self::$Category->select('title')->where('id',$product->sub_sub_category_id)->first();
				$slug3 = ucwords($category3->title);
			}
			
			$breadcrumb = ucwords($slug1).' >> '.ucwords($slug2);
			if($slug3 != ""){
				$breadcrumb = $breadcrumb.' >> '.ucwords($slug3);
			}
			
			$breadcrumb = $breadcrumb.' >> '.ucwords(strtolower($product->product_name));
			
			if($product->brand_id > 0){
				$brandDetails = self::$Brands->select('title','slug','banner')->where('id',$product->brand_id)->first();
				$brandDetails->banner = env('SITE_URL').'public/assets/images/admin/gallery/'.$brandDetails->banner;
				$product->brand_data = $brandDetails;
			}
			$p_image = [];
			$images = self::$ProductImages->where('product_id',$product->id)->orderBy('ordering','ASC')->get()->take(6);
			foreach($images as $key => $image){
				$p_image[$key]['image'] = env('SITE_URL').'public/assets/images/admin/products/'.$image->image_name;
			}
			
			if(count($p_image) == 0){
				$p_image[0]['image'] = env('SITE_URL').'public/assets/images/no-img.jpg';
			}
			
			$product->rating = rand(1,5);
			$product->no_of_reviews = number_format(rand(1001,9999));
			$product->no_of_rating = number_format(rand(1001,9999));
			
			$product->product_images = $p_image;
			$product->price = $product->price;
			$product->discounted_price = $product->discounted_price;
			$product->discount_apply = $product->saling_price > 0 ? true : false;
			$product->list_price = $product->discounted_price > 0 ? number_format($product->discounted_price) : number_format($product->saling_price);
			$discountPercentage = 0;
			if($product->price > 0 && $product->saling_price > 0){
				$priceDiff = $product->price-$product->saling_price;
				$discountPercentage = ($priceDiff/$product->price)*100;
			}
			
			$product->discount_percentage = ceil($discountPercentage);
			
			$product->price = number_format($product->price);
			
			$productFeatures = [];
			if($product->features != ""){
				$productFeatures = explode('||',$product->features);
			}
			
			$product->product_features = $productFeatures; 
			
			
			#related products
			$related_products = self::$Products->select('id','product_name','price','discounted_price','slug','saling_price')->where('sub_category_id',$product->sub_category_id)->where('status',1)->where('id','!=',$product->id)->inRandomOrder()->get()->take(12);
			foreach($related_products as $key => $related_product){
				
				$isWishlist2 = 0;
				if($request->u && $request->u > 0){
					$wishlist2 = self::$Wishlists->where('product_id',$related_product->id)->where('user_id',$request->u)->where('status',1)->count();
					$isWishlist2 = $wishlist2;
				}
				
				$p_image2 = env('SITE_URL').'public/assets/images/no-img.jpg';
				$image2 = self::$ProductImages->where('product_id',$related_product->id)->orderBy('ordering','ASC')->first();
				if(isset($image2->id)){
					$p_image2 = env('SITE_URL').'public/assets/images/admin/products/'.$image2->image_name;
				}
				
				$discountPercentage = 0;
				if($related_product->price > 0 && $related_product->saling_price > 0){
					$priceDiff = $related_product->price-$related_product->saling_price;
					$discountPercentage = ($priceDiff/$related_product->price)*100;
				}
				
				$related_product->discount_percentage = ceil($discountPercentage);
				
				
				$related_product->rating = rand(1,5);
				$related_product->no_of_reviews = number_format(rand(1001,9999));
				
				$related_product->product_banner = $p_image2;
				$related_product->is_wishlist = $isWishlist2;
				$related_product->price = number_format($related_product->price);
				$related_product->discounted_price = number_format($related_product->discounted_price, 2, '.', '');
				$related_product->discount_apply = $related_product->saling_price > 0 ? true : false;
				$related_product->list_price = $related_product->discounted_price > 0 ? number_format($related_product->discounted_price) : number_format($related_product->saling_price);
				$related_product->title = strlen($related_product->product_name) > 20 ? substr($related_product->product_name,0,20).'...' : $related_product->product_name;
				//$related_product->title = $related_product->product_name;
			}
			$isWishlist = 0;
			if($request->u && $request->u > 0){
				$wishlist = self::$Wishlists->where('product_id',$product->id)->where('user_id',$request->u)->where('status',1)->count();
				$isWishlist = $wishlist;
			}
			
			return response()->json(['success'=>true,'is_wishlist' => $isWishlist, 'breadcrumb' => $breadcrumb, 'product'=>$product,'related_products' => $related_products],200);
		}
		
		
	}

}