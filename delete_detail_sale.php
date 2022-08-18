<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $delete_id = delete_by_id('sale_detail',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Producto fue retirado exitosamente");
      redirect('add_sale.php');
  } else {
      $session->msg("d","Se ha producido un error en la eliminación del producto");
      redirect('add_sale.php');
  }
?>
