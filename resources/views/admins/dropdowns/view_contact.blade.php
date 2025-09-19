@extends('layout.admin')
@section('title', 'View Contact') 

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>View Contact</h2>
        </div>
        <!-- Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="body">                            
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                	@include('../flash-message')
                                    <div class="body">
                                        <div class="form-group form-float">
                                        	<label class="form-label">Name</label>
                                            <div class="form-line">
                                                <input type="text" name="name" id="name" value="{{ $contact->name }}" class="form-control">                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                        	<label class="form-label">Email</label>
                                            <div class="form-line">
                                                <input type="text" name="email" id="email" value="{{ $contact->email }}" class="form-control">                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                        	<label class="form-label">Phone</label>
                                            <div class="form-line">
                                                <input type="text" name="phone" id="phone" value="{{ $contact->phone }}" class="form-control">                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                        	<label class="form-label">Subject</label>
                                            <div class="form-line">
                                                <input type="text" name="subject" id="subject" value="{{ $contact->subject }}" class="form-control">                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                        	<label class="form-label">Message</label>
                                            <div class="form-line">
                                                <textarea name="message" id="message" rows="6" class="form-control">{{ $contact->message }}</textarea>                                            </div>
                                        </div>
                                        <a href="{{ url('admins/contacts') }}"><button type="button" class="btn btn-primary m-t-15 waves-effect">Back</button></a>
                                   </div>
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

@endsection