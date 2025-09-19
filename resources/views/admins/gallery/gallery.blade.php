@extends('layout.admin')
@section('title', 'Gallery')

@section('content')

<script src="{{ URL::asset('public/assets/plugins/dropzone/dropzone.js') }}"></script>
<link href="{{ URL::asset('public/assets/plugins/dropzone/dropzone.css') }}" rel="stylesheet">


<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Gallery</h2>
    </div>
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2> Gallery </h2>            
          </div>
          <div class="body">
            <div id="my-awesome-dropzone" class="">
              <div class="dz-message">
                <div class="drag-icon-cph"> <i class="material-icons">touch_app</i> </div>
                <h3>Drop files here or click to upload gallery.</h3>
                </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">          
          <div class="body">
            <div class="table-responsive">
              <div class="dataTables_wrapper form-inline dt-bootstrap">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Image</th>
                      <th class="text-center">Created</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Image</th>
                      <th class="text-center">Created</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  
                  @if(isset($galleries) && $galleries->count() > 0)
                  @foreach($galleries as $key => $gallery)
                  @php
                  $isExists = 0;
                  @endphp
                  <tr class="text-center">
                    <td>{{ $key+1; }}</td>
                    <td>
                    	<a href="{{ URL::asset('public/assets/images/admin/gallery/') }}/{!! $gallery->image !!}" data-sub-html="Demo Description">
                            <img width="100px" class="img-responsive thumbnail" src="{{ URL::asset('public/assets/images/admin/gallery/') }}/{!! $gallery->image !!}">
                        </a>
                    </td>
                    <td>{{ date("F jS, Y h:i A",strtotime($gallery->created_at)); }}</td>
                    <td>
                    <a title="Delete Image" class="btn btn-danger" onClick="deleteImg('{!! Crypt::encrypt($gallery->id) !!}')" href="javascript:void(0)">Delete</a>
                    </td>
                  </tr>
                  @endforeach
                  
                  @else
                  <tr>
                    <td align="center" colspan="9">Record not found</td>
                  </tr>
                  @endif
                    </tbody>
                </table>
              </div>
            </div>
            {!! $galleries->appends(request()->except('page','_token'))->links('pagination.custom') !!} </div>
        </div>
      </div>      
    </div>
  </div>       
</section>

<script type="text/javascript">
	/**************delete banner image*****************/
    function deleteImg(rowId){
        if (rowId != '') {
            swal({
				title: "Do you want to delete this gallery image?",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				cancelButtonText: "No",
				confirmButtonText: 'Yes',
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					swal("Deleted!", "", "success");
					$.ajax({
						type: 'POST',
						url: "{{ url('admins/delete-gallery-image') }}",
						data: {rowId: rowId},
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						success: function(msg){
							window.location.href="{{ $_SERVER['REQUEST_URI'] }}";
						},
						error: function(ts) {
							$('#error500').modal('show');
						}
					})
				} else {
					swal("Cancelled", "", "error");
				}
			});
			return false;
        }
    }
	
	$('#my-awesome-dropzone').attr('class', 'dropzone');
    var myDropzone = new Dropzone('#my-awesome-dropzone', {
        url: "{{ url('admins/upload-gallery-images') }}",
        clickable: true,
        method: 'POST',
        maxFiles: 50,
        parallelUploads: 50,
        maxFilesize: 20,
        addRemoveLinks: false,
        dictRemoveFile: 'Remove',
        dictCancelUpload: 'Cancel',
        dictCancelUploadConfirmation: 'Confirm cancel?',
        dictDefaultMessage: 'Drop files here to upload',
        dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
        dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
        paramName: 'file',
		//params: {'pid':''},
        forceFallback: false,
        createImageThumbnails: true,
        maxThumbnailFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.webp",
        //acceptedFiles: "image/*",
        autoProcessQueue: true,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
        init: function() {
            this.on('thumbnail', function(file) {
                if (file.width < 100 || file.height < 100){
                    file.rejectDimensions();
                } else {
                    file.acceptDimensions();
                }
            });
        },
        accept: function(file, done){
            file.acceptDimensions = done;
            file.rejectDimensions = function(){
                done('The image must be at least 300 x 300px')
            };
        }
    });
    
    myDropzone.on("complete", function(file){
        var status = file.status;
        if(status == 'success'){
			swal("Gallery images uploaded successfully", "", "success");
        }
        console.log(file);
    });
    
    var count = 1;
    myDropzone.on("success", function(file, responseText){
        var fnamenew = file.name;
        count++;
    });
    
    myDropzone.on("removedfile", function(file) {
        var fname = file.name;
        fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
    });
    
    myDropzone.on("addedfile", function(file) {
    
    }); 
</script>
@endsection