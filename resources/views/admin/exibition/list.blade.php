@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')
    <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
    @endsection
<div class="">
        <div class="allbutntbl">
        <a href="{{ $page_details['Action_route']}}"
        class="btn btn-success"
        
        ><i class="fa fa-plus" aria-hidden="true"
        
        ></i> Add exibition</a>
        </div>
        </div>
       
        <div class="col-sm-12">
        <form role="form" class="form-element" action="{{ route('exibition',base64_encode(0)) }}" method="post" enctype="multipart/form-data">
			   @csrf
         
            <div class="col-sm-3 col-md-3">
							<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="">
              
						</div>
            <div class="col-sm-3 col-md-3">
              <input type="hidden" name="serach" value="serach" >
            <button type="submit" class="btn btn-primary searchBtn sOrdersearch"  >Search</button>
          </div>
          </form>
			
        </div>
       
        <div class="col-sm-12">
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
      
	<a class="nav-item nav-link <?php echo ($type==0)?'active':''; ?>"  href="{{ route('exibition',base64_encode(0)) }}">On-going exhibitions </a>
	<a class="nav-item nav-link <?php echo ($type==1)?'active':''; ?>"  href="{{ route('exibition',base64_encode(1)) }}">Completed exhibitions</a>

  </div>
</nav>
</div>
<div class="col-sm-12">
    
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            
                            <th>Exibition name</th>
                            <th>Exibition code</th>
                            <th>Exibition start date</th>
                            <th>Exibition start time</th>
                            <th>Exibition end date</th>
                            <th>Exibition end time</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($description_data as $description)
           
						<tr>
							<td>{{$description->exibition_name}}</td>
							<td>{{$description->exibition_code}}</td>
              <td>{{$description->startdate}}</td>
              <td>{{$description->starttime}}</td>
              <td>{{$description->enddate}}</td>
              <td>{{$description->endtime}}</td>

							<td>
                    <a href="{{route('edit_exibition', [base64_encode($description->id)])}}"
                    onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
                    >
                          <i class="fa fa-pencil text-blue" aria-hidden="true"></i>
                    </a>&nbsp;|&nbsp;
                    <a href="{{route('delete_exibition', base64_encode($description->id))}}"
                    onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                    >
                    <i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                    @if($description->status == '0')
                    <a class="btn shadow-box btn-success" href="{{ route('exibitionapprove', $description->id ) }}"><i class="fa fa-ban"></i></a>
                    @else
                    <a class="btn shadow-box btn-success" href="{{ route('exibitiondeapprove', $description->id ) }}"><i class="fa fa-check"></i></a>
                    @endif
							    </td>

							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>

						  
 </form>
</div>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">
$(function() {

  $('input[name="daterange"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear',
		  format: 'YYYY-MM-DD'
      }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + '/' + picker.endDate.format('YYYY-MM-DD'));
	  $('.daterange').trigger('change');
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>
@endsection

