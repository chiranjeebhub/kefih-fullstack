@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')
    <a href="" class="btn btn-default">Go Back</a>
    @endsection

<div class="col-sm-12">
    
<form role="form" class="form-element" action="" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            
                            <th>Image</th>
                            <th>Remove</th>
                          
						</tr>
					</thead>
					<tbody>
					
					  @foreach($data as $description)
                                <tr>
                                    <td> <img src="{{ asset('uploads/review/'.$description->uploads) }}" alt="" width="50" height="50"> </td>
                                    <td>
                                <a href="{{route('remove_snapbooks', base64_encode($description->id))}}"
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

@endsection

