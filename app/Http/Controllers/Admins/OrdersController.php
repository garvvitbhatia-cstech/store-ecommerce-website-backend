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
use App\Models\Orders;
 
class OrdersController extends Controller{

	public function orders(Request $request){
		$conditions = array();
		$cond = array();		
		if($request->input('invoice_id') != ''){
			$cond['invoice_id'] = array('invoice_id', $request->input('invoice_id'));
		}
		if($request->input('customer_name') != ''){
			$cond['customer_name'] = array('customer_name', 'like', '%'.$request->input('customer_name').'%');
		}
		if($request->input('customer_email') != ''){
			$cond['customer_email'] = array('customer_email', 'like', '%'.$request->input('customer_email').'%');
		}
		if($request->input('customer_contact') != ''){
			$cond['customer_contact'] = array('customer_contact', 'like', '%'.$request->input('customer_contact').'%');
		}
		if($request->input('online_transaction_id') != ''){
			$cond['online_transaction_id'] = array('online_transaction_id', $request->input('online_transaction_id'));
		}
		if($request->input('payment_status') != ''){
			$cond['payment_status'] = array('payment_status', $request->input('payment_status'));
		}
		if($request->input('item_status') != ''){
			$cond['item_status'] = array('item_status', $request->input('item_status'));
		}
		$i = 0;
		foreach($cond as $value){
			$conditions[$i] = $value;
			$i++;
		}
		$pages = Orders::where($conditions)->latest()->paginate(PAGE_LIMIT);
		return view('admins.orders.orders',compact('pages'));
	} 

	public function view_order(Request $request, $id){
		$order = Orders::where('id',Crypt::decrypt($id))->first();				
		if(!empty($order)){
			return view('admins.orders.view_order',compact('order'));
		}else{
			return redirect('admins/orders');
		}			
	}	

}