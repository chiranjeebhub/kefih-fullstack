   <?php 
    $info=DB::table('orders')->where('id',substr($data['order_no'], -3))->first();
   
    ?>
    Thank you for shopping at jaldikharido.com. We are preparing your
    Bag of Joy. We will deliver your order placed on {{ date('d-m-Y H:i:s',strtotime(@$data['order_date']))}}. Order ID {{substr($data['order_no'], -3)}}. You can expect the delivery by [{{@$info->delivery_date}}] [{{@$info->delivery_time}}].
    Stay Stylish!
    
    