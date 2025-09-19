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
use App\Models\User;
use App\Models\Gallery;
use App\Models\Banner;
 
class GalleryController extends Controller{

	public function gallery(Request $request){
		$galleries = Gallery::latest()->paginate(20);	
		return view('admins.gallery.gallery',compact('galleries'));
	}
	
	public function upload_gallery_images(Request $request){
		if($request->ajax()){
            if(!empty($_FILES)){
                $msg = "Error";
                $fileName = $_FILES['file']['name']; //Get the image
                $file_full = base_path().'/public/assets/images/admin/gallery/'; //Image storage path
                $file_temp_name = $_FILES['file']['tmp_name'];
                $pathInfo = pathinfo(basename($fileName));
                $ext = $request->file->extension();
                $checkImage = getimagesize($file_temp_name);
				$actual_image_name = date('d_m_Y_H_i_'.mt_rand(111, 999).'_a.').$request->file->extension();
				$destination2 = base_path().'/public/assets/images/admin/gallery/';
                if($checkImage !== false){
					if($request->file->move($destination2, $actual_image_name)){
						$gallery = new Gallery();
						$gallery->image = $actual_image_name;
						$gallery->save();
						$msg = "Success";
					}
                }
            }
            echo json_encode(array('msg' => $msg));
        }
        exit;
	}

	public function delete_gallery_image(Request $request){
		if($request->ajax()){
			$msg = 'Error';
			$postData = $request->all();
			if(isset($postData) & !empty($postData)){
				$table = $request->input('Gallery');
				$ids = Crypt::decrypt($request->input('rowId'));
				$record = Gallery::where(['id' => $ids])->first();
				if(isset($record->id)){
					$destination = base_path().'/public/assets/images/admin/gallery/';
					if(file_exists($destination.$record->image)){
						unlink($destination.$record->image);
					}
					Gallery::where('id',$ids)->delete();
				}
				$msg = "Success";
			}
			echo json_encode(array('msg' => $msg));
		}
		exit;
	}
	
	public function deleteProductImage(){
		$this->viewBuilder()->setLayout('false');
        if($this->request->is(AJAX)){
            $postData = $this->request->getData();
            if(!empty($postData)){
                $rowId = $this->decryptData($postData['rowId']);
                $table = TableRegistry::get(PRODUCTIMAGES);
                $deleteRecord = $table->find()->where(array(ID => $rowId))->first();
                $imageName = $deleteRecord->image_name;
                if(file_exists(WWW_ROOT.'img/products/'.$imageName)){
                    unlink(WWW_ROOT.'img/products/'.$imageName);
                }
                $record = $table->get($rowId);
                $table->delete($record);
            }
        }
        exit;	
	}
	
	public function banners(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('page') != ''){
			$cond['page'] = array('page', 'like', '%'.$request->input('page').'%');
		}
		if($request->input('heading') != ''){
			$cond['heading'] = array('heading', 'like', '%'.$request->input('heading').'%');
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$banners = Banner::where($conditions)->latest()->paginate(PAGE_LIMIT);		
		return view('admins.gallery.banners',compact('banners'));
	}
	
	public function add_banner(Request $request){			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'banner' => 'required|mimes:jpeg,png,jpg,webp'
			],[
				'banner.required' => 'Please select image.',
				'banner.mimes' => 'Please select on jpeg, png, jpg, webp image only.',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$image = NULL;
				if(!empty($request->file('banner'))){
					$actual_image_name2 = time().rand().'.'.$request->banner->extension();  
					$destination2 = base_path().'/public/assets/images/admin/banners/';
					if($request->banner->move($destination2, $actual_image_name2)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination2.$request->input('old_banner'))){
								unlink($destination2.$request->input('old_banner'));
							}
						}
						$image = $actual_image_name2;
					}
				}	
							
				$banner = new Banner();
				$banner->heading = $request->heading;
				$banner->page = $request->page;
				$banner->text = $request->text;
				$banner->url = $request->url;
				$banner->banner = $image;
				$banner->status = $status;
				$banner->save();
				return redirect('admins/banners')->with('success','Banner has been uploaded successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		return view('admins.gallery.add_banner');	
	}
	
	public function edit_banner(Request $request, $id){
		$banner = Banner::where('id',Crypt::decrypt($id))->first();			
		$postData = $request->all();				
		if(!empty($postData)){
			$request->validate([
				'banner' => 'mimes:jpeg,png,jpg,webp'
			],[
				'banner.mimes' => 'Please select on jpeg, png, jpg, webp image only.',
			]);
			
			try {
				$status = 0;
				if(isset($request->status)){
					$status = 1;	
				}
				$image = $request->input('old_banner');
				if(!empty($request->file('banner'))){
					$actual_image_name2 = time().rand().'.'.$request->banner->extension();  
					$destination2 = base_path().'/public/assets/images/admin/banners/';
					if($request->banner->move($destination2, $actual_image_name2)){
						if($request->input('old_banner') != ""){
							if(file_exists($destination2.$request->input('old_banner'))){
								unlink($destination2.$request->input('old_banner'));
							}
						}
						$image = $actual_image_name2;
					}
				}	
							
				$banner = Banner::find($banner->id);
				$banner->heading = $request->heading;
				$banner->page = $request->page;
				$banner->text = $request->text;
				$banner->url = $request->url;
				$banner->banner = $image;
				$banner->status = $status;
				$banner->save();
				return back()->with('success','Banner has been updated successfully');
			}
			catch(\Exception $e){
				return back()->with('error','Something went wrong');
			}
		}	
		if(!empty($banner)){
			return view('admins.gallery.edit_banner',compact('banner'));
		}else{
			return redirect('admins/banners');
		}	
	}

}