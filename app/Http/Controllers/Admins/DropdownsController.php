<?php
namespace App\Http\Controllers\Admins;
 
use Hash;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Item; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Contacts;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Colors;
use App\Models\Sizes;
use App\Models\Brands;
 
class DropdownsController extends Controller{
	
	private static $Category;
	private static $Brands;
	private static $ProductImages;
	private static $Products;
	
	
	public function __construct(){
		self::$Category = new Category();
		self::$Brands = new Brands();
		self::$Products = new Products();
		self::$ProductImages = new ProductImages();
	}

	public function categories(Request $request){
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
		$pages = Category::where($conditions)->where('status','!=',3)->latest()->paginate(100);
		return view('admins.dropdowns.categories',compact('pages'));
	}

	public function add_category(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:categories',
				'parent_id' => 'required',
				//'category_banner' => 'required|mimes:jpg,png,jpeg|max:2048',
				//'category_icon' => 'required|mimes:jpg,png,jpeg|max:2048'
			],[
			
				'title.required' => 'Please enter category title.',
				'title.unique' => 'Category already exists.',
				'parent_id.required' => 'Please choose parent category',
				//'category_banner.required' => 'Please choose category banner.',
				//'category_banner.mimes' => 'Please choose only jpg,png,jpeg image',
				//'category_icon.required' => 'Please choose category icon.',
				//'category_icon.mimes' => 'Please choose only jpg,png,jpeg image'				
			]);
			
			try {
				$category_banner = $category_icon = NULL;
				if(!empty($request->file('category_banner'))){
					$actual_image_name = time().rand().'.'.$request->category_banner->extension();  
					$destination = base_path().'/public/assets/images/admin/categories/';
					if($request->category_banner->move($destination, $actual_image_name)){
						$category_banner = $actual_image_name;
					}
				}
				if(!empty($request->file('category_icon'))){
					$actual_image_name2 = time().rand().'.'.$request->category_icon->extension();  
					$destination2 = base_path().'/public/assets/images/admin/categories/';
					if($request->category_icon->move($destination2, $actual_image_name2)){
						$category_icon = $actual_image_name2;
					}
				}
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
	
				$category = new Category;
				$category->title = $request->title;
				$category->parent_id = $request->parent_id;
				$category->slug = Str::slug($request->title);
				$category->category_banner = $category_banner;
				$category->category_icon = $category_icon;
				$category->status = $status;
				$category->save();	
				return redirect('admins/category')->with('success','Category has been created successfully');	
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}			
		}
		$categories = $this->categoryTypeList();
		$categoryList = Category::where(['status' => 1, 'parent_id' => 0])->get(['id','title']);
		return view('admins.dropdowns.add_category',compact('categoryList','categories'));	
	}
	
	public function edit_category(Request $request, $id){
		$category = Category::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'parent_id' => 'required',
				'title' => 'required|unique:categories,title,'.$category->id
			],[
				'parent_id.required' => 'Please enter category title',
				'title.required' => 'Please enter category title',
				'title.unique' => 'Category already exists.'
			]);
			
			try {
				$category_banner = $category->category_banner;
				$category_icon = $category->category_icon;
				if(!empty($request->file('category_banner'))){
					$actual_image_name = time().rand().'.'.$request->category_banner->extension();  
					$destination = base_path().'/public/assets/images/admin/categories/';
					if($request->category_banner->move($destination, $actual_image_name)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination.$request->input('old_banner'))){
								unlink($destination.$request->input('old_banner'));
							}
						}
						$category_banner = $actual_image_name;
					}
				}
				
				if(!empty($request->file('category_icon'))){
					$actual_image_name2 = time().rand().'.'.$request->category_icon->extension();  
					$destination2 = base_path().'/public/assets/images/admin/categories/';
					if($request->category_icon->move($destination2, $actual_image_name2)){
						if($request->input('old_icon') != ""){
							if(file_exists($destination2.$request->input('old_icon'))){
								unlink($destination2.$request->input('old_icon'));
							}
						}
						$category_icon = $actual_image_name2;
					}
				}
				$status = 0;
				if(isset($request->status) && $request->status == 1){
					$status = 1;
				}
				$category_row = Category::find($category->id);
				$category_row->title = $request->title;
				$category_row->parent_id = $request->parent_id;
				$category_row->slug = Str::slug($request->title);
				$category_row->category_banner = $category_banner;
				$category_row->category_icon = $category_icon;
				$category_row->status = $status;
				$category_row->save();
				return back()->with('success','Category has been updated successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($category)){
			$categories = $this->categoryTypeList();
			$categoryList = Category::where(['status' => 1, 'parent_id' => 0])->get(['id','title']);
			return view('admins.dropdowns.edit_category',compact('category','categoryList','categories'));
		}else{
			return redirect('admins/category');
		}			
	}
	public function apply_product_offer(Request $request){
		$product = Products::where('id',$request->id)->first();
		$price = $product->saling_price > 0 ? $product->saling_price : $product->price;
		
		if($request->offerType == 'Amount'){
			$discountedAmount = 0;
			if($request->offerValue < $price){
				$discountedAmount = $price - $request->offerValue;
			}
		}else{
			$percentAmt = ($price*$request->offerValue)/100;
			$discountedAmount = $price - $percentAmt;
		}
		
		Products::where('id',$request->id)->update(['discounted_price' => $discountedAmount,'offer_value' => $request->offerValue,'offer_type' => $request->offerType]);
		
		echo 'Success';
	}
	public function products(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('product_name') != ''){
			$cond['product_name'] = array('product_name', 'like', '%'.$request->input('product_name').'%');
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = Products::where($conditions)->where('status','!=',3)->latest()->orderBy('id','DESC')->paginate(100);
		return view('admins.dropdowns.products',compact('pages'));
	}
	public function search_products(Request $request){
		$keyword = strtolower($_REQUEST["q"]);
		if (!$keyword) return;
		$records = DB::table('products')->select('id','model_no')->where('status',1)->where('model_no', 'like', $keyword.'%')->take(10)->get();
		$countRecord = $records->count();
		$html = '[';
		foreach($records as $key => $record):
			$html.= '{"id":"' . $record->id . '","label":"' . $record->model_no . '","value":"' . $record->model_no . '","name":"' . $record->model_no . '"}';
			if (($countRecord - 1) != $key){
				$html.= ',';
			}
		endforeach;
		$html.= ']';
		echo $html;
		die;
	}
	public function delete_category_bulk(Request $request){
		$itemIds = array_unique($request->rowIds);
		foreach($itemIds as $key => $itemId){
			Category::where('id',$itemId)->update(['status' => 3]);	
		}
		echo 'Success'; die;
	}
	public function delete_product_bulk(Request $request){
		$itemIds = array_unique($request->rowIds);
		foreach($itemIds as $key => $itemId){
			Products::where('id',$itemId)->update(['status' => 3]);	
		}
		echo 'Success'; die;
	}
	public function update_product_bulk(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$fileName = $_FILES["products"]["tmp_name"];
			if(isset($fileName) && !empty($fileName)){
				$csvMimes = array('application/csv', 'text/csv');
				if(!empty($_FILES['products']['name']) && $_FILES["products"]["size"] > 0 && in_array($_FILES['products']['type'], $csvMimes)){
					$file = fopen($fileName, "r");
					$num = 1;
					while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
						if($num > 1){
							if($column[0] > 0){
								$productImages[0] = isset($column[35]) && $column[35] != "" ? $column[35] : '';
								$productImages[1] = isset($column[36]) && $column[36] != "" ? $column[36] : '';
								$productImages[2] = isset($column[37]) && $column[37] != "" ? $column[37] : '';
								$productImages[3] = isset($column[38]) && $column[38] != "" ? $column[38] : '';
								$productImages[4] = isset($column[39]) && $column[39] != "" ? $column[39] : '';
								
								$cateID = 0;
								if($column[2] != ""){
									$cateData = DB::table('categories')->where('title',trim($column[2]))->first();
									if(isset($cateData->id)){
										$cateID = $cateData->id;
									}else{
										$cateData = self::$Category->create(['title' => ucwords(trim($column[2])),'slug' => Str::slug(trim($column[2]))]);
										$cateID = $cateData->id;
									}
								}
								
								$subcateID = 0;
								if($column[3] != ""){
									$subcateData = DB::table('categories')->where('title',trim($column[3]))->first();
									if(isset($subcateData->id)){
										$subcateID = $subcateData->id;
									}else{
										$subcateData = self::$Category->create(['parent_id' => $cateID, 'title' => ucwords(trim($column[3])),'slug' => Str::slug(trim($column[3]))]);
										$subcateID = $subcateData->id;
									}
								}
								
								$subsubcateID = 0;
								if($column[4] != ""){
									$subsubcateData = DB::table('categories')->where('title',trim($column[4]))->first();
									if(isset($subsubcateData->id)){
										$subsubcateID = $subsubcateData->id;
									}else{
										$subsubcateData = self::$Category->create(['parent_id' => $subcateID, 'title' => ucwords(trim($column[4])),'slug' => Str::slug(trim($column[4]))]);
										$subsubcateID = $subsubcateData->id;
									}
								}
								
								$brandID = 0;
								if($column[5] != ""){
									$brandData = DB::table('brands')->where('title',trim($column[5]))->first();
									if(isset($brandData->id)){
										$brandID = $brandData->id;
									}else{
										$brandData = self::$Brands->create(['title' => ucwords(trim($column[5])),'slug' => Str::slug(trim($column[5]))]);
										$brandID = $brandData->id;
									}
								}
								$setData['product_name'] = ucwords(utf8_encode($column[1]));
								$setData['category_id'] = $cateID;
								$setData['sub_category_id'] = $subcateID;
								$setData['sub_sub_category_id'] = $subsubcateID;
								$setData['brand_id'] = $brandID;
								$setData['unit'] = $column[6];
								$setData['price'] = $column[7];
								$setData['saling_price'] = $column[8];
								$setData['description'] = htmlentities($column[10]);
								$setData['discounted_price'] = $column[9];
								$setData['slug'] = Str::slug($column[1]).'-'.rand(10001,99999);
								if(!empty($column[11])){
									$setData['is_wholesale'] = trim($column[11]) == 'Yes' ? 1 : 2;
								}
								$setData['minimum_order_qty'] = $column[12] > 0 ? $column[12] : 100;
								
								$setData['model_no'] = $column[13];
								$setData['color'] = $column[14];
								$setData['capacity'] = $column[15];
								$setData['material'] = $column[16];
								$setData['type'] = $column[17];
								
								$setData['pack_of'] = $column[18];
								$setData['features'] = $column[19];
								$setData['warranty'] = $column[20];
								$setData['packing_sizing'] = $column[21];
								$setData['product_care'] = $column[22];
								$setData['height'] = $column[23];
								$setData['width'] = $column[24];
								$setData['length'] = $column[25];
								$setData['breadth'] = $column[26];
								
								$setData['hsn'] = $column[27];
								$setData['country_origin'] = $column[28];
								$setData['manufractur_details'] = $column[29];
								$setData['packer_details'] = $column[30];
								$setData['min_order_qty'] = $column[31] > 0 ? $column[31] : 1;
								$setData['sales_package'] = $column[32];
								$setData['search_keywords'] = $column[33];
								$setData['video_url'] = $column[34];
								$setData['gst_tax'] = $column[40];
								
								$setData['quantity'] = 100;
								
								$setData['keywords'] = '';
								$setData['seo_title'] = '';
								$setData['seo_keywords'] = '';
								$setData['seo_description'] = '';
								$setData['robot_tags'] = 'index,follow';
								$setData['status'] = 1; 
								
								$result = self::$Products->where('id',$column[0])->update($setData);
								
								$pOrdering = self::$ProductImages->where('product_id',$column[0])->orderBy('ordering','DESC')->first();
								if(isset($pOrdering->id)){
									$counter = $pOrdering->ordering+1;
								}else{
									$counter = 1;
								}
								
								foreach($productImages as $key => $productImage){
									if(!empty($productImage)){
										$path_info = pathinfo($productImage);
										$ext = $path_info['extension'];
										$actual_image_name = time().$key.'.'.$ext;
										$destination = base_path().'/public/assets/images/admin/products/';
										$img = $destination.$actual_image_name;
										file_put_contents($img, file_get_contents($productImage));
										
										$setData2['product_id'] = $column[0];
										$setData2['image_name'] = $actual_image_name;
										$setData2['ordering'] = $counter;
										self::$ProductImages->create($setData2);
										$counter++;
										
									}
								}
							}
						}
						$num++;
					}
					return redirect('admins/products')->with('success','Product has been created successfully');
				}else{
					echo 'InvalidFileType'; die;
				}
			}else{
				echo 'ChoseFile'; die;
			}
		}
		return view('admins.dropdowns.update_product_bulk');
	}
	public function export_product(Request $request){
		
		$delimiter = ",";
		$filename = "anupam_products_for_update.csv";
		
		$destination = "storage/export-csv/".$filename;
		$f = fopen($destination,"w");
		
		$fields = array(
						'P ID',
						'Product Name',
						'Category',
						'Sub Category',
						'Sub Category',
						'Brand',
						'Unit',
						'MRP',
						'List Price',
						'Discounted Price',
						'Description',
						'Is Wholesale',
						'Minimum Order Quantity Wolesale',
						'Model No',
						'Color',
						'Capacity',
						'Material',
						'Type',
						'Pack Of',
						'Features',
						'Warranty',
						'Packing Size',
						'Product Care',
						'Height',
						'Width',
						'Length',
						'Breadth',
						'HSN',
						'Country Origin',
						'Manufractur Details',
						'Packer Details',
						'Minimum Order Quantity',
						'Sales package',
						'Search KeyWords',
						'Youtube Video Url',
						'Image One',
						'Image Two',
						'Image Three',
						'Image Four',
						'Image Five',
						'GST TAX'
						);
		 
		fputcsv($f, $fields, $delimiter);
		
		$itemIds = array_unique($request->rowIds);
		foreach($itemIds as $key => $itemId){
			$productData = Products::where('id',$itemId)->first();
			
			$cateName = '';
			if($productData->category_id != "" && $productData->category_id > 0){
				$cateData = DB::table('categories')->where('id',$productData->category_id)->first();
				if(isset($cateData->id)){
					$cateName = $cateData->title;
				}
			}
			
			$cateName2 = '';
			if($productData->sub_category_id != "" && $productData->sub_category_id > 0){
				$cateData2 = DB::table('categories')->where('id',$productData->sub_category_id)->first();
				if(isset($cateData2->id)){
					$cateName2 = $cateData2->title;
				}
			}
			
			$cateName3 = '';
			if($productData->sub_sub_category_id != "" && $productData->sub_sub_category_id > 0){
				$cateData3 = DB::table('categories')->where('id',$productData->sub_sub_category_id)->first();
				if(isset($cateData3->id)){
					$cateName3 = $cateData3->title;
				}
			}
			
			$brandName = '';
			if($productData->brand_id != "" && $productData->brand_id > 0){
				$brandData = DB::table('brands')->where('id',$productData->brand_id)->first();
				if(isset($brandData->id)){
					$brandName = $brandData->title;
				}
			}
			
			$isWholesale = $productData->is_wholesale == 1 ? 'Yes' : 'No';
			
			$lineData = array(
							$productData->id,
							$productData->product_name,
							$cateName,
							$cateName2,
							$cateName3,
							$brandName,
							$productData->unit,
							$productData->price,
							$productData->saling_price,
							$productData->discounted_price,
							$productData->description,
							$isWholesale,
							$productData->minimum_order_qty,
							$productData->model_no,
							$productData->color,
							$productData->capacity,
							$productData->material,
							$productData->type,
							$productData->pack_of,
							$productData->features,
							$productData->warranty,
							$productData->packing_sizing,
							$productData->product_care,
							$productData->height,
							$productData->width,
							$productData->length,
							$productData->breadth,
							$productData->hsn,
							$productData->country_origin,
							$productData->manufractur_details,
							$productData->packer_details,
							$productData->min_order_qty,
							$productData->sales_package,
							$productData->search_keywords,
							$productData->video_url,
							'',
							'',
							'',
							'',
							'',
							$productData->gst_tax,
							);
			fputcsv($f, $lineData, $delimiter);
			
		}
		$lineData2 = array('','');						
		fputcsv($f, $lineData2, $delimiter);                     
		
		fclose ($f);

		//move back to beginning of file
		//fseek($f, 0);
		//set headers to download file rather than displayed
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header("Cache-Control: max-age=0");	
		
		echo env('APP_URL').$destination; die; 
	}
	public function import_category(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
				$fileName = $_FILES["products"]["tmp_name"];
				if(isset($fileName) && !empty($fileName)){
				$csvMimes = array('application/csv', 'text/csv');
				if(!empty($_FILES['products']['name']) && $_FILES["products"]["size"] > 0 && in_array($_FILES['products']['type'], $csvMimes)){
					$file = fopen($fileName, "r");
					$num = 1;
					while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
						if($num > 1){
							if($column[2] != ""){
								
								$catExist = DB::table('categories')->where('title',trim($column[2]))->where('status','!=',3)->count();
								
								if($catExist == 0){
								
									$cateID = 0;
									if($column[1] != ""){
										$cateData = DB::table('categories')->where('status','!=',3)->where('title',trim($column[1]))->first();
										if(isset($cateData->id)){
											$cateID = $cateData->id;
										}else{
											$cateData = self::$Category->create(['parent_id' => 0, 'status' => 1, 'title' => ucwords(trim($column[1])),'slug' => Str::slug(trim($column[1]))]);
											$cateID = $cateData->id;
										}
									}
									
									$actual_image_name = '';
									if($column[3] != ""){
										$path_info = pathinfo($column[3]);
										$ext = $path_info['extension'];
										$actual_image_name = time().'.'.$ext;
										$destination = base_path().'/public/assets/images/admin/categories/';
										$img = $destination.$actual_image_name;
										file_put_contents($img, file_get_contents($column[3]));
									}
									
									$category = new Category;
									$category->title = ucwords(strtolower($column[2]));
									$category->parent_id = $cateID;
									$category->slug = Str::slug($column[2]);
									$category->category_banner = $actual_image_name;
									$category->category_icon = '';
									$category->status = 1;
									$category->save();
								
								}
								
								
							}
						}
						$num++;
					}
					return redirect('admins/category')->with('success','Category has been created successfully');	
				}else{
					echo 'InvalidFileType'; die;
				}
			}else{
				echo 'ChoseFile'; die;
			}
		}
		return view('admins.dropdowns.import_category');	
	}
	public function import_product(Request $request){
		//header("Content-Type: text/html; charset=ISO-8859-1");
		$postData = $request->all();
		if(!empty($postData)){
			$fileName = $_FILES["products"]["tmp_name"];
			if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['products']['name']) && $_FILES["products"]["size"] > 0 && in_array($_FILES['products']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					
					if($num > 1){
						
						$productImages[0] = isset($column[35]) && $column[35] != "" ? $column[35] : '';
						$productImages[1] = isset($column[36]) && $column[36] != "" ? $column[36] : '';
						$productImages[2] = isset($column[37]) && $column[37] != "" ? $column[37] : '';
						$productImages[3] = isset($column[38]) && $column[38] != "" ? $column[38] : '';
						$productImages[4] = isset($column[39]) && $column[39] != "" ? $column[39] : '';
						//print_r(trim($column[1])); die;
						//echo $column[1]; die;
						//$count = Products::where('product_name',trim('HandPainted Artwork Painting Canvas '))->count();
						//if($count == 0){
						//print_r($count); die;
							$cateID = 0;
							if($column[2] != ""){
								$cateData = DB::table('categories')->where('title',trim($column[2]))->first();
								if(isset($cateData->id)){
									$cateID = $cateData->id;
								}else{
									$cateData = self::$Category->create(['title' => ucwords(trim($column[2])),'slug' => Str::slug(trim($column[2]))]);
									$cateID = $cateData->id;
								}
							}
							
							$subcateID = 0;
							if($column[3] != ""){
								$subcateData = DB::table('categories')->where('title',trim($column[3]))->first();
								if(isset($subcateData->id)){
									$subcateID = $subcateData->id;
								}else{
									$subcateData = self::$Category->create(['parent_id' => $cateID, 'title' => ucwords(trim($column[3])),'slug' => Str::slug(trim($column[3]))]);
									$subcateID = $subcateData->id;
								}
							}
							
							$subsubcateID = 0;
							if($column[4] != ""){
								$subsubcateData = DB::table('categories')->where('title',trim($column[4]))->first();
								if(isset($subsubcateData->id)){
									$subsubcateID = $subsubcateData->id;
								}else{
									$subsubcateData = self::$Category->create(['parent_id' => $subcateID, 'title' => ucwords(trim($column[4])),'slug' => Str::slug(trim($column[4]))]);
									$subsubcateID = $subsubcateData->id;
								}
							}
							
							$brandID = 0;
							if($column[5] != ""){
								$brandData = DB::table('brands')->where('title',trim($column[5]))->first();
								if(isset($brandData->id)){
									$brandID = $brandData->id;
								}else{
									$brandData = self::$Brands->create(['title' => ucwords(trim($column[5])),'slug' => Str::slug(trim($column[5]))]);
									$brandID = $brandData->id;
								}
							}
							
							$setData['product_name'] = ucwords(utf8_encode($column[1]));
							$setData['category_id'] = $cateID;
							$setData['sub_category_id'] = $subcateID;
							$setData['sub_sub_category_id'] = $subsubcateID;
							$setData['brand_id'] = $brandID;
							$setData['unit'] = $column[6];
							$setData['price'] = $column[7];
							$setData['saling_price'] = $column[8];
							$setData['description'] = htmlentities($column[10]);
							$setData['discounted_price'] = $column[9];
							$setData['slug'] = Str::slug($column[1]).'-'.rand(10001,99999);
							if(!empty($column[11])){
								$setData['is_wholesale'] = trim($column[11]) == 'Yes' ? 1 : 2;
							}
							$setData['minimum_order_qty'] = $column[12] > 0 ? $column[12] : 100;
							
							$setData['model_no'] = $column[13];
							$setData['color'] = $column[14];
							$setData['capacity'] = $column[15];
							$setData['material'] = $column[16];
							$setData['type'] = $column[17];
							
							$setData['pack_of'] = $column[18];
							$setData['features'] = $column[19];
							$setData['warranty'] = $column[20];
							$setData['packing_sizing'] = $column[21];
							$setData['product_care'] = $column[22];
							$setData['height'] = $column[23];
							$setData['width'] = $column[24];
							$setData['length'] = $column[25];
							$setData['breadth'] = $column[26];
							
							$setData['hsn'] = $column[27];
							$setData['country_origin'] = $column[28];
							$setData['manufractur_details'] = $column[29];
							$setData['packer_details'] = $column[30];
							$setData['min_order_qty'] = $column[31] > 0 ? $column[31] : 1;
							$setData['sales_package'] = $column[32];
							$setData['search_keywords'] = $column[33];
							$setData['video_url'] = $column[34];
							$setData['gst_tax'] = $column[40];
							
							$setData['quantity'] = 100;
							
							$setData['keywords'] = '';
							$setData['seo_title'] = '';
							$setData['seo_keywords'] = '';
							$setData['seo_description'] = '';
							$setData['robot_tags'] = 'index,follow';
							$setData['status'] = 1; 
							
							//echo '<pre>';
							//print_r($setData); die;
							
							$result = self::$Products->CreateRecord($setData);
							
							$counter = 1;
							foreach($productImages as $key => $productImage){
								if(!empty($productImage)){
									$path_info = pathinfo($productImage);
									$ext = $path_info['extension'];
									$actual_image_name = time().$key.'.'.$ext;
									$destination = base_path().'/public/assets/images/admin/products/';
									$img = $destination.$actual_image_name;
									file_put_contents($img, file_get_contents($productImage));
									
									$setData2['product_id'] = $result->id;
									$setData2['image_name'] = $actual_image_name;
									$setData2['ordering'] = $counter;
									self::$ProductImages->create($setData2);
									$counter++;
									
								}
							}
							
						//}
					}
					$num++;
				}
				return redirect('admins/products')->with('success','Product has been created successfully');	
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
		}
		return view('admins.dropdowns.import_product');	
	}
	public function cleanString($text) {
		$utf8 = array(
			'/[áàâãªä]/u'   =>   'a',
			'/[ÁÀÂÃÄ]/u'    =>   'A',
			'/[ÍÌÎÏ]/u'     =>   'I',
			'/[íìîï]/u'     =>   'i',
			'/[éèêë]/u'     =>   'e',
			'/[ÉÈÊË]/u'     =>   'E',
			'/[óòôõºö]/u'   =>   'o',
			'/[ÓÒÔÕÖ]/u'    =>   'O',
			'/[úùûü]/u'     =>   'u',
			'/[ÚÙÛÜ]/u'     =>   'U',
			'/ç/'           =>   'c',
			'/Ç/'           =>   'C',
			'/ñ/'           =>   'n',
			'/Ñ/'           =>   'N',
			'/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
			'/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
			'/[“”«»„]/u'    =>   ' ', // Double quote
			'/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
		);
		return preg_replace(array_keys($utf8), array_values($utf8), $text);
	}
	public function add_product(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'price' => 'required',
				'product_name' => 'required|unique:products',
				'quantity' => 'required',
				'keywords' => 'required'
			],[
				'price.required' => 'Please enter product price',
				'product_name.required' => 'Please enter product name.',
				'product_name.unique' => 'Product already exists.',
				'quantity.required' => 'Please enter product quantity',
				'keywords.required' => 'Please enter product keywords.'
			]);
			
			try {				
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				
				$is_wholesale = 2;
				if(isset($request->is_wholesale)){
					$is_wholesale = 1;	
				}
				$is_featured = 2;
				if(isset($request->is_featured)){
					$is_featured = 1;	
				}
	
				$category_id = $sub_category_id = NULL;
				if($request->parent_id != ''){
					if(is_array($request->parent_id)){
						$category_id = implode(',',$request->parent_id);
					}else{
						$category_id = $request->parent_id;
					}
				}
				if($request->sub_category_id != ''){
					if(is_array($request->sub_category_id)){
						$sub_category_id = implode(',',$request->sub_category_id);
					}else{
						$sub_category_id = $request->sub_category_id;
					}
				}				
				$product = new Products;
				$product->category_id = $category_id;
				$product->sub_category_id = $sub_category_id;
				$product->sub_sub_category_id = $request->sub_sub_category_id;
				$product->brand_id = $request->brand_id;
				$product->product_name = $request->product_name;
				$product->description = $request->description;
				$product->price = $request->price;
				$product->discounted_price = $request->discounted_price;
				$product->slug = Str::slug($request->product_name);
				$product->quantity = $request->quantity;
				$product->keywords = $request->keywords;
				$product->seo_title = $request->seo_title;
				$product->seo_keywords = $request->seo_keywords;
				$product->seo_description = $request->seo_description;
				$product->robot_tags = $request->robot_tags;
				$product->minimum_order_qty = $request->minimum_order_qty;
				$product->is_wholesale = $is_wholesale;
				$product->status = $status;
				$product->is_featured = $is_featured;
				
				$product->model_no = $request->model_no;	
				$product->color = $request->color;	
				$product->capacity = $request->capacity;	
				$product->material = $request->material;	
				$product->type = $request->type;	
				$product->pack_of = $request->pack_of;	
				$product->features = $request->features;	
				$product->warranty = $request->warranty;	
				$product->packing_sizing = $request->packing_sizing;	
				$product->product_care = $request->product_care;
				
				$product->height = $request->height;
				$product->width = $request->width;
				$product->length = $request->length;
				$product->breadth = $request->breadth;
				$product->hsn = $request->hsn;
				
				$product->country_origin = $request->country_origin;	
				$product->manufractur_details = $request->manufractur_details;	
				$product->packer_details = $request->packer_details;	
				$product->min_order_qty = $request->min_order_qty;	
				$product->sales_package = $request->sales_package;	
				$product->search_keywords = $request->search_keywords;	
				$product->video_url = $request->video_url;	
				
				$product->save();	
				return redirect('admins/products')->with('success','Product has been created successfully');	
			}
			catch(\Exception $e){
				//print_r($e->getMessage());die;
				return back()->with('error','Something went wrong');
			}			
		}
		$categories = $this->categoryTypeList();
		$categoryList = Category::where(['status' => 1, 'parent_id' => 0])->get(['id','title']);
		$brands = Brands::where('status',1)->orderBy('title','ASC')->get();
		return view('admins.dropdowns.add_product',compact('categoryList','categories','brands'));	
	}
	
	public function get_sub_categories(Request $request){
		$postData = $request->all();
		$html = '<option value="">Select Sub Category</option>';
		if(!empty($postData)){
			$category_id = $postData['cat_id'];
			if($category_id[0] != ''){
				foreach($category_id[0] as $key => $value){			
					$subcategories = Category::where('parent_id',$value)->get();
					foreach($subcategories as $key => $subcategory){		
						$html .= '<option value="'.$subcategory->id.'">'.ucwords($subcategory->title).'</option>';
					}
				}			
			}
			return $html;
		}
		exit;
	}
	
	public function get_sub_sub_categories(Request $request){
		$postData = $request->all();
		$html = '<option value="">Select Sub Category</option>';
		if(!empty($postData)){
			$subcategories = Category::where('parent_id',$postData['cat_id'])->get();
			foreach($subcategories as $key => $subcategory){		
				$html .= '<option value="'.$subcategory->id.'">'.ucwords($subcategory->title).'</option>';
			}
			return $html;
		}
		exit;
	}
	
	public function edit_product(Request $request, $id){
		$product = Products::where('id',Crypt::decrypt($id))->first();
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'price' => 'required',
				'product_name' => 'required',
			],[
				'price.required' => 'Please enter product price',
				'product_name.required' => 'Please enter product name.',
			]);
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$is_wholesale = 2;
				if(isset($request->is_wholesale)){
					$is_wholesale = 1;	
				}
				$is_featured = 2;
				if(isset($request->is_featured)){
					$is_featured = 1;	
				}
				if(isset($postData['ordering']) && !empty($postData['ordering'])){
					foreach($postData['ordering'] as $keys => $vals){
						$productImage = ProductImages::find($postData['orderingEditId'][$keys]);
						$productImage->ordering = $vals;
						$productImage->save();
					}
				}
				$category_id = $sub_category_id = NULL;
				if($request->parent_id != ''){
					if(is_array($request->parent_id)){
						$category_id = implode(',',$request->parent_id);
					}else{
						$category_id = $request->parent_id;
					}
				}
				if($request->sub_category_id != ''){
					if(is_array($request->sub_category_id)){
						$sub_category_id = implode(',',$request->sub_category_id);
					}else{
						$sub_category_id = $request->sub_category_id;
					}
				}
	
				$product = Products::find($product->id);
				$product->category_id = $category_id;
				$product->sub_category_id = $sub_category_id;
				$product->sub_sub_category_id = $request->sub_sub_category_id;
				$product->brand_id = $request->brand_id;
				$product->product_name = $request->product_name;
				$product->description = $request->description;
				$product->price = $request->price;
				$product->discounted_price = $request->discounted_price;
				$product->slug = Str::slug($request->product_name);
				$product->quantity = $request->quantity;
				$product->keywords = $request->keywords;
				$product->seo_title = $request->seo_title;
				$product->seo_keywords = $request->seo_keywords;
				$product->seo_description = $request->seo_description;
				$product->robot_tags = $request->robot_tags;
				$product->minimum_order_qty = $request->minimum_order_qty;	
				
				$product->model_no = $request->model_no;	
				$product->color = $request->color;	
				$product->capacity = $request->capacity;	
				$product->material = $request->material;	
				$product->type = $request->type;	
				$product->pack_of = $request->pack_of;	
				$product->features = $request->features;	
				$product->warranty = $request->warranty;	
				$product->packing_sizing = $request->packing_sizing;	
				$product->product_care = $request->product_care;
				
				$product->height = $request->height;
				$product->width = $request->width;
				$product->length = $request->length;
				$product->breadth = $request->breadth;
				$product->hsn = $request->hsn;	
				
				$product->country_origin = $request->country_origin;	
				$product->manufractur_details = $request->manufractur_details;	
				$product->packer_details = $request->packer_details;	
				$product->min_order_qty = $request->min_order_qty;	
				$product->sales_package = $request->sales_package;	
				$product->search_keywords = $request->search_keywords;	
				$product->video_url = $request->video_url;	
							
				$product->is_wholesale = $is_wholesale;
				$product->status = $status;
				$product->is_featured = $is_featured;
				
				$product->varient_ids = $request->input('varient_ids');
				
				
				/*if(empty($product->sku)){
					$sku = $this->productCode($product->product_name, $product->id);
					$product->sku = $product->id;
				}*/
				$product->save();
				return back()->with('success','Product has been updated successfully');
			}
			catch(\Exception $e){
				print_r($e->getMessage());die;
				return back()->with('error','Something went wrong');
			}			
		}
		if(isset($product->id)){
			$productImages = ProductImages::where('product_id',$product->id)->orderBy('ordering','asc')->get();
			if($productImages->count() > 0){
				$i=1;
				foreach($productImages as $key=> $image){
					$productImage = ProductImages::find($image->id);
					$productImage->image_alt = '';
					$productImage->image_title = '';
					$productImage->save();
				}
			}						
			$productImages = ProductImages::where(['product_id' => $product->id])->orderBy('ordering','ASC')->get();
			$categories = $this->categoryTypeList();
			$categoryList = Category::where(['status' => 1, 'parent_id' => 0])->get(['id','title']);
			$brands = Brands::where('status',1)->orderBy('title','ASC')->get();
			
			return view('admins.dropdowns.edit_product',compact('categoryList','categories','product','productImages','brands'));
		}else{
			return redirect('admins/products');
		}
	}
	
				
	public function upload_product_images(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
            if(!empty($_FILES)){
                $msg = "Error";
                $fileName = $_FILES['file']['name']; //Get the image
                $file_full = base_path().'/public/assets/images/admin/products/';
				$actual_image_name2 = time().rand().'.'.$request->file->extension();  
                $file_temp_name = $_FILES['file']['tmp_name'];
                $pathInfo = pathinfo(basename($fileName));
                $ext = $pathInfo['extension'];
                $checkImage = getimagesize($file_temp_name);				
                if($checkImage !== false){
                    if($request->file->move($file_full, $actual_image_name2)){
						$max_order = ProductImages::where('product_id', $_REQUEST['pid'])->max('ordering');
						if($max_order == ''){
							$ordering = 1;
						}else{
							$ordering = $max_order+1;
						}						
                        $saveData = new ProductImages;
						$saveData->product_id = $_REQUEST['pid'];
                        $saveData->image_name = $actual_image_name2;
						$saveData->ordering = $ordering;
						$saveData->save();	
                        $msg = $actual_image_name2;
                    }
                }
            }
            echo $msg;
        }
        exit;
	}
	
	public function delete_product_image(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){
				$ids = Crypt::decrypt($request->input('rowId'));
				$data = ProductImages::where('id',$ids)->first();				
				$destination = base_path().'/public/assets/images/admin/products/';
				if(file_exists($destination.$data->image_name)){
					unlink($destination.$data->image_name);
				}
				ProductImages::where('id',$ids)->delete();
				$msg = "success";
			}			
			echo $msg;
		}
		exit;
	}
	
	public function contacts(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('read_status') != ''){
			$cond['read_status'] = array('read_status', $request->input('read_status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = Contacts::where($conditions)->latest()->paginate(PAGE_LIMIT);
		return view('admins.dropdowns.contacts',compact('pages'));
	}

	public function view_contact(Request $request, $id){
		$contact = Contacts::where('id',Crypt::decrypt($id))->first();
				
		if(!empty($contact)){
			Contacts::where('id', $contact->id)->update(['read_status' => 1]);
			return view('admins.dropdowns.view_contact',compact('contact'));
		}else{
			return redirect('admins/contacts');
		}			
	}
	
	public function colors(Request $request){
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
		$pages = Colors::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.dropdowns.colors',compact('pages'));
	}
	
	public function add_color(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:colors,title',
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
							
				$color = new Colors();
				$color->title = $request->title;
				$color->slug = Str::slug($request->title);
				$color->status = $status;
				$color->save();
				return redirect('admins/colors')->with('success','Color has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.dropdowns.add_color');	
	}
	
	public function edit_color(Request $request, $id){
		$color = Colors::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:colors,title,'.$color->id,
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}				
							
				$color = Colors::find($color->id);
				$color->title = $request->title;
				$color->slug = Str::slug($request->title);
				$color->status = $status;
				$color->save();
				return back()->with('success','Color has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($color)){
			return view('admins.dropdowns.edit_color',compact('color'));
		}else{
			return redirect('admins/colors');
		}
		
	}
	
	public function sizes(Request $request){
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
		$pages = Sizes::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.dropdowns.sizes',compact('pages'));
	}
	
	public function add_size(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:sizes,title',
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$banner_status = 0;
				if(isset($request->banner_status)){
					$banner_status = 1;	
				}	
							
				$tag = new Sizes();
				$tag->title = $request->title;
				$tag->status = $status;
				$tag->save();
				return redirect('admins/sizes')->with('success','Size has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.dropdowns.add_size');	
	}
	
	public function edit_size(Request $request, $id){
		$size = Sizes::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:sizes,title,'.$size->id,
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}				
							
				$tag = Sizes::find($size->id);
				$tag->title = $request->title;
				$tag->status = $status;
				$tag->save();
				return back()->with('success','Tag has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($size)){
			return view('admins.dropdowns.edit_size',compact('size'));
		}else{
			return redirect('admins/sizes');
		}
		
	}
	
	public function categoryTypeList(){
		return CategoryType::where('status',1)->pluck('title','id');
	}

}