@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<div class="col-xs-12 col-sm-7 col-md-8">
  <div class="contactform">
    <h6>Get In <span>Touch</span>
    </h6>
    <form role="form" class="form-element" action="{{route('contact-us')}}" method="post"> @csrf 
      
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <input type="text" class="form-control" id="name" placeholder="Name" name="name">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <input type="text" class="form-control" id="mobile" maxlength="10" placeholder="Mobile Number" name="phone">
          </div>
        </div>
      </div>
      <div class="form-group">
        <input type="email" class="form-control" id="email" placeholder="Email" name="email">
      </div>
      <div class="form-group">
        <textarea class="form-control" name="message" data-required-error="Please fill the field" placeholder="Message"></textarea>
      </div>
      <div class="row">
        <div class="col-sm-6 col-sm-pull-0 col-md-4 col-md-pull-0">
          <input type="submit" name="subscription" value="Send Message" class="registrbtn btn btn-success">
        </div>
      </div>
    </form>
  </div>
</div> 
@endsection
