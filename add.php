<?php
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('-1'. mysql_error());
  }
mysql_select_db("order_now", $con);
$json_array = array();
	
	$query = "select * from orders where stat in (1,2) and cust_id=".$_REQUEST['cust_id'];
	// die($query);
	$result = mysql_query($query,$con); 
	if(mysql_num_rows($result)){
			$row = mysql_fetch_array($result);
			$order_id = $row['id'];
			$status = $row['stat'];
	}else{
		echo "here now";
		$date = getdate();
		$query = "insert into orders(cust_id, date_added, stat, name) values(".$_REQUEST['cust_id'].", 
			now(), 1, 'order-".$date['mday']."-".$date['month']."-".$date['year']."-".$date['hours'].":".$date['minutes']."')";
		$result = mysql_query($query,$con);
		$order_id=mysql_insert_id();
		$status=1;
	}
	
	//die($order_id);
	if($status == 1){
		$query = "insert into order_details(order_id, item_id, quant) values($order_id,".$_REQUEST['item_id'].",".$_REQUEST['quant'].")";
		$rsp['response'] = "Item added";
	}else{
		$query = "insert into requests(order_id, item_id, quant, type, approved) 
		values($order_id,".$_REQUEST['item_id'].",".$_REQUEST['quant'].",1, 0)";
		$rsp['response'] = "Request sent for approval";
	}
	$json_array = array();
	mysql_query($query, $con);
	array_push($json_array,$rsp);
	echo json_encode($json_array);
mysql_close($con);
?>