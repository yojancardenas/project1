<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $id_sd     = (int)$_GET['id'];
  $sd        = find_by_id('sale_detail',$id_sd);
  if ((int)$sd['qty']>1) {
      $qty       = -1;
      $update    = update_product_detail_sale_qty($qty,(int)$id_sd);
      if($update){
          $session->msg("s","Producto fue retirado exitosamente");
          redirect('add_sale.php');
      } else {
          $session->msg("d","Se ha producido un error en la eliminación del producto");
          redirect('add_sale.php');
      }
  } else {
        $session->msg("d","Debe eliminar el producto de la lista");
        redirect('add_sale.php');
  }

?>