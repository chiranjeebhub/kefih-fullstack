 <?php 
 $colors=App\Products::getProductsAttributes2('Colors',0,$prd_detail->id);
  $sizes=App\Products::getProductsAttributes2('Sizes',0,$prd_detail->id);
 ?>
 
 <div class="table-responsive">
       <table>
           
        @if (count($sizes)>0)
          <tr>
            <th width="50%">Size</th> 
            <td>
               <?php foreach($sizes as $size){?>
                  <span class="badge">{!!App\Products::getAttrName('Sizes',$size['size_id'])!!}</span>
                <?php } ?>
              
                </td> 
            </tr> 
        @endif

           
            @if (count($colors)>0)
                <tr>
                <th> Color </th>
                <td>
                     <?php foreach($colors as $color){
                     $color_data=App\Products::getcolorNameAndCode('Colors',$color['color_id'])
                     ?>
                     <span style="background-color:{{$color_data->color_code}};" title="{{$color_data->name}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <?php } ?>
                </td> 
                </tr> 
                 @endif
                
                  
                
                
                 
                 
               
                
                 @if ($prd_detail->material!='')
                 <tr>
                     <th>Material</th>
                <th>  {!!App\Products::meterialname($prd_detail->material)!!} </th>
                <!--<td></td>--> 
                </tr>
                  @endif
                
                
                
             
      </table> 
       </div> 