<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $s_id = $_GET['id'];
  $sd   = find_sale_detail($s_id);

if (count($sd)==0) {
  $delete_id = delete_by_id('sales',$s_id);
  $session->msg("s","Pre venta eliminada exitosamente");
  redirect('add_sale.php');
} else{
foreach ($sd as $key):
  echo $key['id'].'<br>';
  echo count($sd).'<br>';
  $delete_id = delete_by_id('sale_detail',$key['id']);
endforeach;
$session->msg("s","Producto fue retirado exitosamente");
redirect('add_sale.php');
}

?>
