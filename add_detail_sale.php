<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $qty       = 1;
  $update    = update_product_detail_sale_qty($qty,(int)$_GET['id']);
  if($update){
      $session->msg("s","Producto agregado exitosamente");
      redirect('add_sale.php');
  } else {
      $session->msg("d","Se produjo un error en la eliminación del producto");
      redirect('add_sale.php');
  }
?>
