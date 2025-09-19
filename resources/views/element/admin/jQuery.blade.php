<script src="{{ URL::asset('public/assets/js/admin/sweet-alert.min.js') }}"></script>

<link rel="stylesheet" href="{{ URL::asset('public/assets/css/admin/sweet-alert.css') }}"/>

<script>

/******change status*****/

function changeStatus(model,dataToken,currentStatus,divID){
	if(dataToken != "" && currentStatus != ""){ 	
		$('#material_icons_'+divID).html('...');
		var current_status = $('#current_status'+divID).val();
		var url ='';
		$.ajax({
			type: 'POST',
			url: "{{url('admins/changeStatus')}}",			
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {model:model, dataToken:dataToken, currentStatus:current_status},
			success: function(msg){				 
					searchData();					
				},error: function(ts){
				$('#error500').modal('show');
			}
		});
	}
}

/******Reset form********/
function resetFilterForm(){
	$('#searchForm')[0].reset();
		$('.searchOptions, .searchbuttons').hide();
  		searchData();
 	}

/*************Save Ordering***************/
function saveOrder(rowId,order,model,currVal){
	if(rowId != '' && order != '' && model != '' && currVal != '' && $.isNumeric(rowId) && $.isNumeric(order) && $.isNumeric(currVal)){			
		$.ajax({
			type:'POST',
			url:"{{url('admins/updateOrder')}}", 
			async:false,
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data:{id:rowId,prev:order,curval:currVal,modal:model},
			success: function(response){
				searchData();
			},error: function(ts){
				$('#error500').modal('show');
			}							
		});
		return false;
	}else{
		searchData();	
	}	
}

/**************delete record*****************/

function deleteRecord(model,rowId,permission){
	
	if(permission == 0){

		swal({
			title: "Do you want to delete this record?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			cancelButtonText: "No",
			confirmButtonText: 'Yes',
			closeOnConfirm: false,
			closeOnCancel: false
		},

		function(isConfirm){
			if (isConfirm){			  
			  $.ajax({
					type: 'POST',
					url: "{{url('admins/deleteRecord')}}",
					data: {model:model, rowId:rowId},
					headers:{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(msg){
						//swal("Deleted!", "", "success");
						setTimeout(searchData(), 50000);
					},error: function(ts) { 
						$('#error500').modal('show');
					}
				})
			} else {
			  swal("Cancelled", "", "error");
			}
		});
	}else{
		swal({
			title: "Cannot Delete This Record",
			text: "",
			timer: 2000,
			showConfirmButton: false
		});
		
	}
	
}
</script>