<table>
    <thead>
			<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Public name</th>
			<th>Email</th>
			<th>Mobile No.</th>
			<th>Registered Date</th>
			<th>Categories</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Vendors as $Vendor)
<?php  $cats=DB::table('vendor_categories')->where('vendor_id',$Vendor->id)->first();
$cats_array=explode(",",$cats);
$cat_name_array=array();
foreach($cats_array as $cat){
    if($cat!=''){
         $cat_name=DB::table('categories')->where('id',$cat)->first();
         if($cat_name){
           array_push($cat_name_array,$cat_name->name);  
         }
    }
  
}
?>
						<tr>
								<td>{{$Vendor->f_name}}</td>
								<td>{{$Vendor->l_name }}</td>
								<td>{{$Vendor->username}}</td>
								<td>{{$Vendor->public_name}}</td>
								<td>{{$Vendor->email}}</td>
								<td>{{$Vendor->phone}}</td>
								<td>{{date('d-m-Y',strtotime($Vendor->created_at))}}</td>
									<td>{{implode(',',$cat_name_array)}}</td>
								
						</tr>
					    @endforeach
    </tbody>
</table>
