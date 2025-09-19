<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
	
	protected $table = 'products';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
		'sub_category_id',
		'sub_sub_category_id',
		'product_name',
		'slug',
		'sku',
		'brand_id',
		'unit',
		'price',
        'discounted_price',
		'quantity',
		'description',
		'video',
		'keywords',
		'seo_title',
        'seo_keywords',
		'seo_description',
		'robot_tags',
		'is_wholesale',
		'minimum_order_qty',
		'status',
		'is_featured',
		'model_no',
		'color',
		'capacity',
		'material',
		'type',
		'pack_of',
		'features',
		'warranty',
		'packing_sizing',
		'product_care',
		'height',
		'width',
		'length',
		'breadth',
		'hsn',
		'country_origin',
		'manufractur_details',
		'packer_details',
		'min_order_qty',
		'sales_package',
		'search_keywords',
		'video_url',
		'gst_tax',
		'varient_ids',
		'saling_price',
		'offer_value',
		'offer_type',
		'rating',
		'no_of_review'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
	 public function UpdateRecord($Details){
		$Record = $this::where('id', $Details['id'])->update($Details);
		return true;
	}
	public function CreateRecord($Details){
		$Record = $this::create($Details);
		return $Record;
	}
	
}
