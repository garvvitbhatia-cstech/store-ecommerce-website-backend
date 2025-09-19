@extends('layout.admin')
@section('title', 'Edit Banner')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Edit Banner</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
          <div class="body">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> @include('../flash-message')
                  <div class="body">
                  {{ Form::open(array('url' => array('/admins/edit-banner',Crypt::encrypt($banner->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                    @csrf
                    <input type="hidden" name="edit_token" id="edit_token" value="{{ Crypt::encrypt($banner->id); }}">
                    <input type="hidden" name="old_banner" id="old_banner" value="{{ $banner->banner }}">
                    <div class="form-group form-float">
                      <label class="form-label">Page</label>
                      <div class="form-line">
                      	<select name="page" id="page" class="form-control">
                        	<option {{$banner->page == 'Home' ? "selected" : "" }} value="Home">Home</option>
                        </select>
                      </div>
                      @error('page')
                      <label id="page-error" class="error" for="page">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">Heading</label>
                      <div class="form-line">
                        <input type="text" name="heading" id="heading" value="{{ $banner->heading }}" class="form-control">
                      </div>
                      @error('heading')
                      <label id="heading-error" class="error" for="heading">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">Text</label>
                      <div class="form-line">
                        <input type="text" name="text" id="text" value="{{ $banner->text }}" class="form-control">
                      </div>
                      @error('text')
                      <label id="text-error" class="error" for="text">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">URL</label>
                      <div class="form-line">
                        <input type="text" name="url" id="url" value="{{ $banner->url }}" class="form-control">
                      </div>
                      @error('url')
                      <label id="url-error" class="error" for="url">{{ $message }}</label>
                      @enderror 
                    </div>
                    <label class="form-label">Banner (1920 x 671)</label>
                      <div class="form-group form-float">
                      	<img width="300px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/banners/') }}/{!! $banner->banner !!}" />
                         <div class="form-line">
                            <input type="file" name="banner" id="banner" accept="image/jpeg, image/png" class="form-control">
                         </div>
                         @error('banner')
                         <label id="banner-error" class="error" for="banner">{{ $message }}</label>
                         @enderror 
                      </div>
                    <label class="form-label">Status</label>
                    <div class="form-group">
                      <input type="checkbox" id="status" {{$banner->status == 1 ? "checked" : "" }} value="1" name="status" class="filled-in" />
                      <label for="status">Active</label>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                    {{ Form::close() }} </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Input --> 
  </div>
</section>
<script>
$(document).ready(function(e){
		
	$(document).on('click', '#submitBtn',function(){
	
		$('#submitBtn').html('Processing...');
		$('#pageForm').submit();
	
	});

}); 
</script> 
@endsection