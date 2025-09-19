<?php
namespace App\Helpers;
Use DB;

class Helper{
		
	public static function getSubCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){		
		$list = '<option value="0">Root</option>';
		if(!empty($categoryList)){
			foreach($categoryList as $keys => $vals):
				$seleted = '';
				$disabled = '';
				$newList = DB::table('categories')->where('parent_id',$vals->id)->get();
				if($parentId == $vals->id){$seleted = 'selected="selected"';}
				$list .= '<option '.$seleted.' value="'.$vals->id.'">'.ucwords($vals->title).'</option>';
				foreach($newList as $nKey => $nVal):
					$seleted2 = $seleted3 = '';
					$disabled2 = $disabled3 = '';
						if($parentId == $nVal->id){$seleted2 = 'selected="selected"';}
						$list .= '<option '.$seleted2.' value="'.$nVal->id.'"> → '.ucwords($nVal->title).'</option>';
						$newList2 = DB::table('categories')->where('parent_id',$nVal->id)->get();
						foreach($newList2 as $nKey => $nVal2):
						$seleted3 = '';
						$disabled3 = '';
						if($parentId == $nVal2->id){$seleted3 = 'selected="selected"';}
						$list .= '<option '.$seleted3.' value="'.$nVal2->id.'"> → → '.ucwords($nVal2->title).'</option>';
						endforeach;
				endforeach;
			endforeach;
			return $list;
		}
	}
	
	public static function getCategoryName($category_id = NULL){
		$category = DB::table('categories')->where('id',$category_id)->first();
		$title = NULL;
		if(isset($category->title)){
			$title = $category->title;	
		}
		return $title;
	}
	
	public static function getProductData($pid = NULL){
		$pdata = DB::table('products')->select('id','model_no')->where('id',$pid)->first();
		return $pdata;
	}
	
	public static function getCategoryData($category_id = NULL){
		$category = DB::table('categories')->where('id',$category_id)->first();
		return $category;
	}


	public static function isParentExist($category_id = NULL){
		$category = DB::table('categories')->where('id',$category_id)->first();
		$count = 0;
		if(isset($category->id)){
			$category = DB::table('categories')->where('parent_id',$category->id)->first();
			if(isset($category->id)){
				$count = 1;	
			}
		}
		return $count;
	}
	
	public static function getCountryById($country_id = NULL, $field = NULL){
		$country = DB::table('countries')->where('id',$country_id)->first();
		if($field != ''){
			$country = DB::table('countries')->select(['id',$field])->where('id',$country_id)->first();				
			if(isset($country->id)){
				$country = $country->$field;
			}	
		}		
		return $country;
	}
	
	public static function getStateById($state_id = NULL, $field = NULL){
		$state = DB::table('states')->where('id',$state_id)->first();
		if($field != ''){
			$state = DB::table('states')->select(['id',$field])->where('id',$state_id)->first();				
			if(isset($state->id)){
				$state = $state->$field;
			}	
		}		
		return $state;
	}
	
	public static function GetProductImage($pid){

		$pData = DB::table('product_images')->where('product_id',$pid)->orderBy('ordering','ASC')->first();	

		return $pData;
	}
	
	public static function getStateListById($country_id = NULL){
		$states = NULL;
		if($country_id != ''){
			$states = DB::table('states')->select(['id','state'])->where('country_id',$country_id)->get();	
		}
		return $states;
	}
	
	public static function getCategoryType($category_id = NULL){
		$category = DB::table('category_type')->where('id',$category_id)->first();
		$title = NULL;
		if(isset($category->id)){
			$title = $category->title;
		}
		return $title;
	}
	
	public static function getCategoryExists($category_id = NULL, $modal = NULL){
		$category = DB::table($modal)->where('id',$category_id)->first();
		$count = 0;
		if(isset($category->id)){
			$count = 1;
		}
		return $count;
	}
	
	public static function getSubCategoryAll($category_id = NULL,$subCatID=NULL){
		$html = '';
		if($category_id != ''){
			$cat_id = explode(',',$category_id);
			foreach($cat_id as $key => $value){
				$subCategories = DB::table('categories')->where('parent_id',$value)->get();
				foreach($subCategories as $key2 => $subCategory){
					$selected = '';
					if($subCategory->id == $subCatID){ $selected = 'selected'; }
					$html .= '<option '.$selected.' value="'.$subCategory->id.'">'.$subCategory->title.'</option>';
				}
			}			
		}
		return $html;
	}

}
?>