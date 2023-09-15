@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<?php 
	$parameters = Request::segment(3);	
	if($parameters=='all')
	{
		$parameters='';
	}
?>

<div class="">
	<div class="allbutntbl">
	    	<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp; 
		<a href="{{ route('add_category') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add  Category</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<!--<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>-->
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-5 hidden-xs"></div>
					<div class="col-md-2 col-xs-12">
						<!--<label>Select Status</label>
						<select class="form-control status" name="status">
							<option value="">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>-->
					</div>
					<div class="col-md-5 col-xs-12">
						<!--<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>-->
					</div>
				</div>
				
				
			</div>
			<div class="col-sm-1">
				<!--<button type="submit" class="btn btn-default reset" >Reset</button>-->
			</div>
		</div>
	</div>
</div>
		
	
					<div class="myadmin-dd dd" id="nestable">
						<ol class="dd-list">
							<?php echo $viewTree; ?>
						</ol>
					</div>
				
					@include('admin.includes.Common_search_and_delete') 
					
				<div id="attributesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content attributesResponseData">
     
     
    </div>

  </div>
</div>

<!--Nestable js -->
    <script src="{{ asset('public/assets/vendor_components/nestable/jquery.nestable.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Nestable
			var updateOutput = function (e) {
                var list = e.length ? e : $(e.target)
                    , output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                }
                else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            $('#nestable').nestable({
                group: 1
            }).on('change', updateOutput);
			updateOutput($('#nestable').data('output', $('#nestable-output')));
            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target)
                    , action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
			
			$('#nestable-menu').nestable();
        });
    </script>

	<script>
		$(document).ready(function () {
			
			$('.dd-item').addClass('dd-collapsed');
			$('[data-action="collapse"]').css('display','none');
			$('[data-action="expand"]').css('display','block');
			
		});
	</script>
@endsection
