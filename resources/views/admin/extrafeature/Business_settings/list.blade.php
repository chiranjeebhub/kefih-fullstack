@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')
    
    @endsection
<div class="">
        <div class="allbutntbl">
        <a href="{{ $page_details['Action_route']}}"
        class="btn btn-success"
        
        ><i class="fa fa-plus" aria-hidden="true"
        
        ></i> Add courier charges</a>
        </div>
        </div>
       
    
       
<div class="col-sm-12">
    
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            <th>Id</th>
                            <th>Courier from weight(GM)</th>
                            <th>Courier to weight(GM)</th>
                            <th>Prices</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($description_data as $description)
           
						<tr>
              <td>{{$description->id}}</td>
							<td>{{$description->from}}</td>
							<td>{{$description->c_to}}</td>
              <td>{{$description->prices}}</td>

							<td>
                    <a href="{{route('edit_couriercharges', [base64_encode($description->id)])}}"
                    onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
                    >
                          <i class="fa fa-pencil text-blue" aria-hidden="true"></i>
                    </a>&nbsp;|&nbsp;
                    <a href="{{route('delete_couriercharges', base64_encode($description->id))}}"
                    onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                    >
                    <i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                   
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

