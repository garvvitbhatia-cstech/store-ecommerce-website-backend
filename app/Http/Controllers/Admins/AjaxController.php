<?php 
namespace App\Http\Controllers\Admins;
 
use Hash;
use Session;
use DB;
use App\Http\Controllers\Controller;  
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


class AjaxController extends Controller{

	public function change_status(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){
				$ids = Crypt::decrypt($request->dataToken);
				$table = $request->input('model');
				$status = $request->input('currentStatus') == 1 ? 2 : 1;
				DB::table($table)->where('id',$ids)->update(['status' => $status]);
				$msg = "success";			
			}			
			echo $msg;
		}
		exit;
	}
	
	public function delete_record(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){
				$table = $request->input('model');
				$ids = Crypt::decrypt($request->input('rowId'));
				
				if($table == 'banners'){
					$record = DB::table($table)->where('id',$ids)->first();
					if($record->banner != ''){
						if(File::exists(public_path('/assets/images/admin/banners/'.$record->banner))){
							unlink(public_path('/assets/images/admin/banners/'.$record->banner));
						}
					}	
				}	
				if($table == 'brands'){
					$record = DB::table($table)->where('id',$ids)->first();
					if($record->banner != ''){
						if(File::exists(public_path('/assets/images/admin/gallery/'.$record->banner))){
							unlink(public_path('/assets/images/admin/gallery/'.$record->banner));
						}
					}	
				}				
				
				DB::table($table)->where('id',$ids)->delete();
				$msg = "success";
				
			}			
			echo $msg;
		}
		exit;
	}
	
	public function update_order(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){				
				$actualVal = 0;
				$id = $request->input('id');
				$prev = $request->input('prev');
				$modal = $request->input('modal');
				$currval = $request->input('curval');
				
				$record =  DB::table($modal)->orderBy('ordering', 'DESC')->get('ordering')->first();
				$actualVal = $record->ordering;
				if($currval <= $actualVal && $currval != 0 && is_numeric($currval)){
					$data = DB::table($modal)->where(array('ordering' => $currval))->get()->first();
					#save current row
					DB::table($modal)->where('id',$id)->update(['ordering' => $currval]);
	
					#save previous row
					DB::table($modal)->where('id',$data->id)->update(['ordering' => $prev]);
				}
				$msg = "success";			
			}			
			echo $msg;
		}
		exit;	
	}

	public function get_state(Request $request){
		if($request->ajax()){
			$country_id = $request->input('countryId');
			$states = DB::table('states')->where('country_id',$country_id)->orderBy('state')->pluck('state','id');

			echo view('admins.ajax.get_state',compact('states'));
		}
		exit;
	}

}
