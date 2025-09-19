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
use App\Models\BlogCategory;
use App\Models\Blogs;
use App\Models\Tags;
 
class BlogsController extends Controller{

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
		$pages = BlogCategory::where($conditions)->latest()->paginate(PAGE_LIMIT);
		return view('admins.blogs.categories',compact('pages'));
	}

	public function add_category(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:blog_category',
			],[
				'title.required' => 'Please enter category title.',
				'title.unique' => 'Category already exists.',			
			]);
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
	
				$category = new BlogCategory;
				$category->title = $request->title;
				$category->slug = Str::slug($request->title);
				$category->description = $request->description;
				$category->status = $status;
				$category->save();
	
				return redirect('admins/blog-categories')->with('success','Category has been created successfully');				
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}
		return view('admins.blogs.add_category');	
	}
	
	public function edit_category(Request $request, $id){
		$category = BlogCategory::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'title' => 'required|unique:blog_category,title,'.$category->id,
			],[
				'title.required' => 'Please enter category title.',
				'title.unique' => 'Category already exists.',			
			]);
			
			try {
				$status = 0;
				if(isset($request->status) && $request->status == 1){
					$status = 1;
				}
				$category_row = BlogCategory::find($category->id);
				$category_row->title = $request->title;
				$category_row->slug = Str::slug($request->title);
				$category_row->description = $request->description;
				$category_row->status = $status;
				$category_row->save();
				return back()->with('success','Blog Category has been updated successfully');	
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}	
		}
		
		if(!empty($category)){
			return view('admins.blogs.edit_category',compact('category'));
		}else{
			return redirect('admins/category');
		}			
	}
	
	public function blogs(Request $request){
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
		$pages = Blogs::where($conditions)->latest()->paginate(PAGE_LIMIT);	
		return view('admins.blogs.blogs',compact('pages'));
	}

	public function add_blog(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'category_id' => 'required',
				'title' => 'required',
				'description' => 'required',
			],[
				'category_id.required' => 'Please select category.',
				'title.required' => 'Please enter title',	
				'description.required' => 'Please enter description.',			
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$tags = NULL;
				if($request->tags != ''){
					$tags = explode(',',$request->tags);
					if(count($tags) > 0){
						foreach($tags as $key => $value){
							$tag_title = Tags::where('title',trim($value))->first();
							if(!isset($tag_title->id)){
								$tagdata = new Tags;
								$tagdata->title = ucwords(trim($value));
								$tagdata->save();
							}
							$new_tag[] = ucwords(trim($value));
						}
						$tags = implode(',',$new_tag);
					}
				}			
	
				$blog = new Blogs;
				$blog->category_id = $request->category_id;
				$blog->title = $request->title;
				$blog->name = $request->name;
				$blog->tags = $tags;
				$blog->slug = Str::slug($request->title);
				$blog->description = $request->description;
				$blog->seo_title = $request->seo_title;
				$blog->seo_description = $request->seo_description;
				$blog->seo_keyword = $request->seo_keyword;
				$blog->status = $status;
				$blog->save();
	
				return redirect('admins/blogs')->with('success','Category has been created successfully');		
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}		
		}
		$category = $this->categoryList();
		return view('admins.blogs.add_blog',compact('category'));	
	}
	
	public function edit_blog(Request $request, $id){
		$blog = Blogs::where('id',Crypt::decrypt($id))->first();			
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'category_id' => 'required',
				'title' => 'required',
				'description' => 'required',
			],[
				'category_id.required' => 'Please select category.',
				'title.required' => 'Please enter title',	
				'description.required' => 'Please enter description.',			
			]);
			
			try {
				$status = 0;
				if(isset($request->status) && $request->status == 1){
					$status = 1;
				}
				$tags = NULL;
				if($request->tags != ''){
					$tags = explode(',',$request->tags);
					if(count($tags) > 0){
						foreach($tags as $key => $value){
							$tag_title = Tags::where('title',trim($value))->first();
							if(!isset($tag_title->id)){
								$tagdata = new Tags;
								$tagdata->title = ucwords(trim($value));
								$tagdata->save();
							}
							$new_tag[] = ucwords(trim($value));
						}
						$tags = implode(',',$new_tag);
					}
				}
				
				$blog = Blogs::find($blog->id);
				$blog->category_id = $request->category_id;
				$blog->title = $request->title;
				$blog->name = $request->name;
				$blog->tags = $tags;
				$blog->slug = Str::slug($request->title);
				$blog->description = $request->description;
				$blog->seo_title = $request->seo_title;
				$blog->seo_description = $request->seo_description;
				$blog->seo_keyword = $request->seo_keyword;
				$blog->status = $status;
				$blog->save();
				return back()->with('success','Blog has been updated successfully');	
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}	
		}

		if(!empty($blog)){
			$category = $this->categoryList();
			return view('admins.blogs.edit_blog',compact('blog','category'));
		}else{
			return redirect('admins/blogs');
		}
	}
	
	public function get_tags(Request $request){
		$blog = Tags::where('title', 'like', '%'.$request->input('query').'%')->orderBy('title')->pluck('title','id');
		$tagsData = array(); 
		foreach($blog as $key => $value){
			$response['id'] = $key; 
			$response['value'] = $value; 
			array_push($tagsData, $response); 
		}
		echo json_encode($tagsData);
		die;
	}

	public function categoryList(){
		return BlogCategory::where('status',1)->pluck('title','id');
	}

}