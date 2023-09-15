<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Id</th>
							<th>name</th>
							<th>Parent</th>
							<th>compare</th>
							<th>View Attributes</th>
							<th>Shown in Nav</th>
							<th>URL</th>
						
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($ledgers as $row)
					  
					
					 	<tr>
						 <tr>

							<td>{{$i++}}</td>
							<td>{{$row->id}}</td>
							<td>{{$row->name}}</td>
							<td>
            <?php 
            $names=App\Category::getParentCategoriesById($row->parent_id,array());
            
             $cats=array();
            $catsss=array();
            $categories = App\Category::
                   with('AllparentCats')
				   ->where('status','!=','0')
				   ->where('isdeleted',0)
				   ->where('parent_id', '!=','0')
                ->where('id',$row->id)
            ->first();
             if($categories){
             array_push($catsss,array('id'=>$categories->id,'name'=>$categories->name));
           $cats=App\Category::list_categories($categories->AllparentCats);
          }

   $cats1=App\Category::array_flatten($cats,array());

   foreach($cats1 as $key=>$c){
      if( ($key % 2)==0){
          array_push($catsss,array('id'=>$c,'name'=>$cats1[$key+1]));
      }
   }
   
   unset ($catsss[count($catsss)-1]);
        $final_cats=array_reverse($catsss);
        $last_index= count($final_cats)-1;
            $i=0;
            $span="";
         foreach($final_cats as $final){
             if($i!=$last_index){
                   $span.="".$final['name'].' => ';
             } else{
                  $span.="".$final['name'].'';
             }
             $i++;
         }
            ?>
			{{$span}}
									   </td>
									   <td>{{($row->cat_compare==0)?' ':'Enabled'}}</td>
									   <td>
								<a href="javascript:void(0)" class="btn btn-primary showcategorySizes" cat_id="{{$row->id}}">Size</a> |
							
								<a href="javascript:void(0)" class="btn btn-primary showcategoryColors" cat_id="{{$row->id}}">Color</a>
								</td>
								
								<td>
								    @if($row->cat_shows_in_nav==0)
								    <span>No</span>
								    @else
								     <span>Yes</span>
								    @endif()
								   
								</td>
								<td>
								  {{URL::to('/cat/'.preg_replace('/\s+/', '', $row->name).'/'.base64_encode($row->id))}}
								</td>
						</tr>
					    @endforeach
						
					</tbody>
				  </table>