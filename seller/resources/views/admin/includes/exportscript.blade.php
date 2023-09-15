<script src="{{asset('public/js/jquery.table2excel.js')}}"></script>
<script type="text/javascript">
$("button").click(function(){
  $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "OrderHistory" //do not include extension
  }); 
});	
</script>	
