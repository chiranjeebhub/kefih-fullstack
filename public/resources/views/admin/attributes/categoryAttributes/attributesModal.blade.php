 <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{$modal_header}}</h4>
      </div>
      <div class="modal-body">
	  <ol>
		<?php foreach($data as $row){	
		?>
		<li>{{$row->name}}</li>
		<?php } ?>
		</ol>
		<a href="{{route('updateAttributes',[ base64_encode($cat_id), base64_encode($attributesType)  ]) }}" class="btn btn-primary">Update {{$modal_header}}</a>
      </div>