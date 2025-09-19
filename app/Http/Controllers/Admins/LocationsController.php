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
use App\Models\Country;
use App\Models\State;
use App\Models\City;
 
class LocationsController extends Controller{

	public function countries(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('country_name') != ''){
			$cond['country_name'] = array('country_name', 'like', '%'.$request->input('country_name').'%');
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = Country::where($conditions)->orderBy('ordering')->paginate(PAGE_LIMIT);	
		return view('admins.locations.countries',compact('pages'));
	}

	public function add_country(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'country_name' => 'required|unique:countries',
				'country_code' => 'required',
				'phonecode' => 'required|numeric',
				'phone_no_format' => 'required'
			],[
				'country_name.required' => 'Please enter country name',
				'country_name.unique' => 'Country already exists',
				'country_code.required' => 'Please enter country code',
				'phonecode.required' => 'Please enter your phonecode',
				'phonecode.numeric' => 'Please enter valid phonecode',
				'phone_no_format.required' => 'Please enter phone number format'
			]);
			
			$status = 0;
			if(isset($request->status)){
				$status = 1;	
			}
			$flag_image = NULL;
			if(!empty($request->file('flag_image'))){
				$actual_image_name = time().rand().'.'.$request->flag_image->extension();  
				$destination = base_path().'/public/assets/images/admin/countries/';
				if($request->flag_image->move($destination, $actual_image_name)){					
					$flag_image = $actual_image_name;
				}
			}
			
			$findOrder = Country::orderBy('ordering', 'DESC')->get('ordering')->first();
			
			$country = new Country;
			if($findOrder->ordering == ''){
				$country->ordering = 1;
			}else{
				$country->ordering = ($findOrder->ordering+1);
			}
			$zipCodeFilterData = array_filter($request->zipcode_format);
			if(!empty($request->zipcode_format)){
				$country->zipcode_format = implode(',',$zipCodeFilterData);
			}
			$country->country_name = $request->country_name;
			$country->country_code = $request->country_code;
			$country->phonecode = $request->phonecode;
			$country->phone_no_format = $request->phone_no_format;
			$country->zipcode_format = $request->zipcode_format;
			$country->flag_image = $flag_image;
			$country->status = $status;			
			$country->save();

			return redirect('admins/countries')->with('success','Country has been created successfully');
		}
		return view('admins.locations.add_country');	
	}
	
	public function edit_country(Request $request, $id){
		$country = Country::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'country_name' => 'required|unique:countries,country_name,'.$country->id,
				'country_code' => 'required',
				'phonecode' => 'required|numeric',
				'phone_no_format' => 'required'
			],[
				'country_name.required' => 'Please enter country name',
				'country_name.unique' => 'Country already exists',
				'country_code.required' => 'Please enter country code',
				'phonecode.required' => 'Please enter your phonecode',
				'phonecode.numeric' => 'Please enter valid phonecode',
				'phone_no_format.required' => 'Please enter phone number format'
			]);
			
			$status = 0;
			if(isset($request->status)){
				$status = 1;	
			}			
			$flag_image = $country->flag_image;
			if(!empty($request->file('flag_image'))){
				$actual_image_name = time().rand().'.'.$request->flag_image->extension();  
				$destination = base_path().'/public/assets/images/admin/countries/';
				if($request->flag_image->move($destination, $actual_image_name)){
					if($request->input('old_image') != ""){
						if(file_exists($destination.$request->input('old_image'))){
							unlink($destination.$request->input('old_image'));
						}
					}
					$flag_image = $actual_image_name;
				}
			}
			
			$country = Country::find($country->id);
			$country->country_name = $request->country_name;
			$country->country_code = $request->country_code;
			$country->phonecode = $request->phonecode;
			$country->phone_no_format = $request->phone_no_format;
			$country->zipcode_format = $request->zipcode_format;
			$country->flag_image = $flag_image;
			$country->status = $status;
			$zipCodeFilterData = array_filter($request->zipcode_format);
			if(!empty($request->zipcode_format)){
				$country->zipcode_format = implode(',',$zipCodeFilterData);
			}
			$country->save();
			return back()->with('success','Country has been updated successfully');		
		}
		
		if(!empty($country)){
			return view('admins.locations.edit_country',compact('country'));
		}else{
			return redirect('admins/countries');
		}
		
	}
	
	public function states(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('state') != ''){
			$cond['state'] = array('state', 'like', '%'.$request->input('state').'%');
		}
		if($request->input('country_id') != ''){
			$cond['country_id'] = array('country_id', $request->input('country_id'));
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = State::where($conditions)->orderBy('state')->paginate(PAGE_LIMIT);
		$country_list = $this->getCountryList();
		return view('admins.locations.states',compact('pages','country_list'));
	}

	public function add_state(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'country_id' => 'required',
				'state' => 'required|unique:states'
			],[
				'country_id.required' => 'Please select country',
				'state.required' => 'Please enter state',
				'state.unique' => 'State already exists',
			]);
			
			$status = 0;
			if(isset($request->status)){
				$status = 1;	
			}

			$state = new State;
			$state->country_id = $request->country_id;
			$state->state = $request->state;
			$state->abbreviation = $request->abbreviation;			
			$state->status = $status;
			$state->save();

			return redirect('admins/states')->with('success','State has been created successfully');
		}
		$country_list = $this->getCountryList();
		return view('admins.locations.add_state',compact('country_list'));
	}
	
	public function edit_state(Request $request, $id){
		$state = State::where('id',Crypt::decrypt($id))->first();				
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([						
				'country_id' => 'required',
				'state' => 'required|unique:states,state,'.$state->id,
			],[
				'country_id.required' => 'Please select country',
				'state.required' => 'Please enter state',
				'state.unique' => 'State already exists',
			]);
			
			$status = 0;
			if(isset($request->status) && $request->status == 1){
				$status = 1;
			}
			$state = State::find($state->id);
			$state->country_id = $request->country_id;
			$state->state = $request->state;
			$state->abbreviation = $request->abbreviation;			
			$state->status = $status;
			$state->save();
			return back()->with('success','State has been updated successfully');		
		}
		
		if(!empty($state)){
			$country_list = $this->getCountryList();
			return view('admins.locations.edit_state',compact('state','country_list'));
		}else{
			return redirect('admins/states');
		}
		
	}
	
	public function cities(Request $request){
		$conditions = array();
		$cond = array();
		if($request->input('city') != ''){
			$cond['city'] = array('city', 'like', '%'.$request->input('city').'%');
		}
		if($request->input('country_id') != ''){
			$cond['country_id'] = array('country_id', $request->input('country_id'));
		}
		if($request->input('state_id') != ''){
			$cond['state_id'] = array('state_id', $request->input('state_id'));
		}
		if($request->input('status') != ''){
			$cond['status'] = array('status', $request->input('status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = City::where($conditions)->orderBy('city')->paginate(PAGE_LIMIT);
		$country_list = $this->getCountryList();
		return view('admins.locations.cities',compact('pages','country_list'));
	}

	public function add_city(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$request->validate([
				'country_id' => 'required',
				'state_id' => 'required',
				'city' => 'required|unique:cities'
			],[
				'country_id.required' => 'Please select country',
				'state_id.required' => 'Please select state',
				'city.required' => 'Please enter city',
				'city.unique' => 'City already exists',
			]);
			
			$status = 0;
			if(isset($request->status)){
				$status = 1;	
			}
			
			$banner = NULL;
			if(!empty($request->file('banner'))){
				$actual_image_name = time().rand().'.'.$request->banner->extension();  
				$destination = base_path().'/public/assets/images/admin/cities/';
				if($request->banner->move($destination, $actual_image_name)){					
					$banner = $actual_image_name;
				}
			}

			$state = new City;
			$state->country_id = $request->country_id;
			$state->state_id = $request->state_id;
			$state->city = $request->city;
			$state->description = $request->description;
			$state->heading = $request->heading;
			$state->slug = Str::slug($request->city);
			$state->banner = $banner;
			$state->seo_title = $request->seo_title;
			$state->seo_description = $request->seo_description;
			$state->seo_keywords = $request->seo_keywords;
			$state->status = $status;
			$state->save();

			return redirect('admins/cities')->with('success','City has been created successfully');
		}
		$country_list = $this->getCountryList();
		return view('admins.locations.add_city',compact('country_list'));
	}
	
	public function edit_city(Request $request, $id){
		$city = City::where('id',Crypt::decrypt($id))->first();
		$postData = $request->all();
		if(!empty($postData)){
			
			$request->validate([
				'country_id' => 'required',
				'state_id' => 'required',
				'city' => 'required|unique:cities,city,'.$city->id,
			],[
				'country_id.required' => 'Please select country',
				'state_id.required' => 'Please select state',
				'city.required' => 'Please enter city',
				'city.unique' => 'City already exists',
			]);
			
			$status = 0;
			if(isset($request->status) && $request->status == 1){
				$status = 1;
			}
			$banner = NULL;
			if(!empty($request->file('banner'))){
				$actual_image_name = time().rand().'.'.$request->banner->extension();  
				$destination = base_path().'/public/assets/images/admin/cities/';
				if($request->banner->move($destination, $actual_image_name)){
					if($request->input('old_image') != ""){
						if(file_exists($destination.$request->input('old_image'))){
							unlink($destination.$request->input('old_image'));
						}
					}
					$banner = $actual_image_name;
				}
			}
			$city = City::find($city->id);
			$city->country_id = $request->country_id;
			$city->state_id = $request->state_id;
			$city->city = $request->city;
			$city->description = $request->description;
			$city->heading = $request->heading;
			$city->slug = Str::slug($request->city);
			$city->banner = $banner;
			$city->seo_title = $request->seo_title;
			$city->seo_description = $request->seo_description;
			$city->seo_keywords = $request->seo_keywords;
			$city->status = $status;
			$city->save();
			return back()->with('success','City has been updated successfully');		
		}
		
		if(!empty($city)){
			$country_list = $this->getCountryList();
			return view('admins.locations.edit_city',compact('city','country_list'));
		}else{
			return redirect('admins/cities');
		}
		
	}

	#zipcode remove
	function removeZipcode(Request $request){
		if($request->ajax()){
			$postData = $request->all();
			$msg = '';
			if(isset($postData) && !empty($postData)){
				$rowId = Crypt::decrypt($request->editId);
				$value = $request->value;
				$tableData = Country::find($rowId);
				$zipCodeFormat = $tableData->zipcode_format;
				$zipcodeArr = explode(',',$zipCodeFormat);
				$arr = array_merge(array_diff($zipcodeArr, array($value)));

				$country = Country::find($rowId);
				$country->zipcode_format = implode(',',$arr);
				$country->save();
				echo "success";
			}
		}
		exit;
	}
	
	public function getCountryList(){
		return Country::where('status',1)->orderBy('country_name')->pluck('country_name','id');		
	}

}