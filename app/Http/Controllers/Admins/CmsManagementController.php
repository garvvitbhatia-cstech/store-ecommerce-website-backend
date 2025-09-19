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
use App\Models\InnerPages;
use App\Models\Testimonials;
use App\Models\Tags;
use App\Models\Brands;
 
class CmsManagementController extends Controller{

	public function inner_pages(Request $request){
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
		$pages = InnerPages::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.cms_management.inner_pages',compact('pages'));
	}
	
	public function edit_inner_page(Request $request, $id){
		$innerpage = InnerPages::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:inner_pages,title,'.$innerpage->id,
				'description' => 'required',
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
				'description.required' => 'Please enter description',
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
				
				$banner = NULL;
				if(!empty($request->file('banner'))){
					$actual_image_name2 = time().rand().'.'.$request->banner->extension();  
					$destination2 = base_path().'/public/assets/images/admin/banners/';
					if($request->banner->move($destination2, $actual_image_name2)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination2.$request->input('old_banner'))){
								unlink($destination2.$request->input('old_banner'));
							}
						}
						$banner = $actual_image_name2;
					}
				}					
							
				$innerpage = InnerPages::find($innerpage->id);
				$innerpage->title = $request->title;
				$innerpage->description = trim($request->description);
				$innerpage->banner = $banner;
				$innerpage->banner_status = $request->banner_status;
				$innerpage->heading = $request->heading;
				$innerpage->sub_heading = $request->sub_heading;
				$innerpage->edit_heading = $request->edit_heading;
				$innerpage->edit_sub_heading = $request->edit_sub_heading;
				$innerpage->edit_description = $request->edit_description;
				$innerpage->seo_title = $request->seo_title;
				$innerpage->seo_description = $request->seo_description;
				$innerpage->seo_keyword = $request->seo_keyword;
				$innerpage->robot_tags = $request->robot_tags;
				$innerpage->status = $status;
				$innerpage->save();
				return back()->with('success','Inner page has been updated successfully');	
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}	
		}
		if(!empty($innerpage)){
			return view('admins.cms_management.edit_inner_page',compact('innerpage'));
		}else{
			return redirect('admins/inner_pages');
		}
		
	}
	
	public function tags(Request $request){
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
		$pages = Tags::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.cms_management.tags',compact('pages'));
	}
	
	public function add_tag(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:tags,title',
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
							
				$tag = new Tags();
				$tag->title = $request->title;
				$tag->status = $status;
				$tag->save();
				return redirect('admins/tags')->with('success','Tag has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.cms_management.add_tag');	
	}
	
	public function edit_tag(Request $request, $id){
		$tag = Tags::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:tags,title,'.$tag->id,
			],[
				'title.required' => 'Please enter title',
				'title.unique' => 'Title already exists',
			]);			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}				
							
				$tag = Tags::find($tag->id);
				$tag->title = $request->title;
				$tag->status = $status;
				$tag->save();
				return back()->with('success','Tag has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($tag)){
			return view('admins.cms_management.edit_tag',compact('tag'));
		}else{
			return redirect('admins/tags');
		}
		
	}
	
	public function testimonials(Request $request){
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
		$pages = Testimonials::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.cms_management.testimonials',compact('pages'));
	}
	
	public function addTestimonial(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'username' => 'required',
				'testimonial' => 'required',
			],[
				'username.required' => 'Please enter username',
				'testimonial.required' => 'Please enter testimonial'
			]);			
			try {
				$profile = 'rest';
				if(!empty($request->file('profile'))){
					$actual_image_name = time().rand().'.'.$request->profile->extension();  
					$destination = base_path().'/public/assets/images/admin/gallery/';
					if($request->profile->move($destination, $actual_image_name)){
						$profile = $actual_image_name;
					}
				}
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}						
				$row = new Testimonials();
				$row->username = $request->username;
				$row->testimonial = $request->testimonial;
				$row->status = $status;
				$row->profile = $profile;
				$row->save();
				return redirect('admins/testimonials')->with('success','Testimonial has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.cms_management.add_testimonial');	
	}
	
	public function editTestimonial(Request $request, $id){
		$testimonial = Testimonials::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'username' => 'required',
				'testimonial' => 'required',
			],[
				'username.required' => 'Please enter username',
				'testimonial.required' => 'Please enter testimonial'
			]);
			try {
				$profile = $testimonial->profile;
				if(!empty($request->file('profile'))){
					$actual_image_name = time().rand().'.'.$request->profile->extension();  
					$destination = base_path().'/public/assets/images/admin/gallery/';
					if($request->profile->move($destination, $actual_image_name)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination.$request->input('old_banner'))){
								unlink($destination.$request->input('old_banner'));
							}
						}
						$profile = $actual_image_name;
					}
				}
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}						
				$testimonial = Testimonials::find($testimonial->id);
				$testimonial->username = $request->username;
				$testimonial->testimonial = $request->testimonial;
				$testimonial->status = $status;
				$testimonial->profile = $profile;
				$testimonial->save();
				return back()->with('success','Testimonial has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($testimonial)){
			return view('admins.cms_management.edit_testimonial',compact('testimonial'));
		}else{
			return redirect('admins/testimonials');
		}
		
	}
	
	public function brands(Request $request){
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
		$pages = Brands::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.cms_management.brands',compact('pages'));
	}
	
	public function addBrand(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required',
			],[
				'title.required' => 'Please enter title',
			]);			
			try {
				$profile = NULL;
				if(!empty($request->file('banner'))){
					$actual_image_name = str_shuffle(time().rand()).'.'.$request->banner->extension();  
					$destination = base_path().'/public/assets/images/admin/gallery/';
					if($request->banner->move($destination, $actual_image_name)){
						$profile = $actual_image_name;
					}
				}
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}						
				$row = new Brands();
				$row->title = $request->title;
				$row->description = $request->description;
				$row->slug = Str::slug($request->title);
				$row->status = $status;
				$row->banner = $profile;
				$row->save();
				return redirect('admins/brands')->with('success','Brand has been created successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.cms_management.add_brand');	
	}
	
	public function editBrand(Request $request, $id){
		$brand = Brands::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'title' => 'required',
			],[
				'title.required' => 'Please enter title',
			]);	
			try {
				$profile = $brand->banner;
				if(!empty($request->file('banner'))){
					$actual_image_name = str_shuffle(time().rand()).'.'.$request->banner->extension();  
					$destination = base_path().'/public/assets/images/admin/gallery/';
					if($request->banner->move($destination, $actual_image_name)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination.$request->input('old_banner'))){
								unlink($destination.$request->input('old_banner'));
							}
						}
						$profile = $actual_image_name;
					}
				}
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}						
				$row = Brands::find($brand->id);
				$row->title = $request->title;
				$row->description = $request->description;
				$row->slug = Str::slug($request->title);
				$row->status = $status;
				$row->banner = $profile;
				$row->save();
				return back()->with('success','Testimonial has been updated successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		
		if(!empty($brand)){
			return view('admins.cms_management.edit_brand',compact('brand'));
		}else{
			return redirect('admins/brands');
		}
		
	}

}