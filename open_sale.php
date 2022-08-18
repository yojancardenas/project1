<?php 
require_once('includes/load.php');
date_default_timezone_set('America/Santiago');
$date		=date('Y-m-d H:i:s');
$user_id	=$_GET['id'];
// status sale: 1=open,2=cancelled
$result=find_open_sale($user_id);
$num_rows 	= count($result);
if ($num_rows >=1) {
	$session->msg("d","Existen ventas sin finalizar");
    redirect('add_sale.php');

}
elseif ($num_rows==0){
	$query = "INSERT INTO sales (";
	$query .="user_id,price_total,date,status";
	$query .=") VALUES (";
	$query .=" '{$user_id}', 0, '','1'";//  status sale: 1=open,2=cancelled
	$query .=")";
	$db->query($query);
	$session->msg("s","Listo para agregar productos");
    redirect('add_sale.php');
}




 ?>