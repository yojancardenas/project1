<?php
  $page_title = 'Agregar venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $products=join_product_table();
   $user=current_user();
   $open_sale=find_open_sale($user['id']);// Busca la venta con status 1(open) de este usuario
?>
<?php

  if(isset($_POST['add_sale'])){
      if (count($open_sale)==0) {
          $session->msg('d','Primero debe crear una venta!.');
          redirect('add_sale.php', false);
      }elseif (count($open_sale)>0) {
          $s_id       = $open_sale[0]['id'];
          $p_id       = $db->escape((int)$_GET['id']);
          $query      = find_by_sql("SELECT * FROM sale_detail sd WHERE sd.sale_id='{$s_id}' AND sd.product_id='{$p_id}' LIMIT 1");
          if(count($query)==0){
            $producto   = find_by_id('products',$p_id);
            $p_name     = $producto['name'];
            $p_price    = $producto['sale_price'];
            $sql  = "INSERT INTO sale_detail (";
            $sql .= " sale_id,product_id,qty,price";
            $sql .= ") VALUES (";
            $sql .= "'{$s_id}','{$p_id}',1,'{$p_price}'";
            $sql .= ")";
            if($db->query($sql)){
              update_product_qty($s_qty,$p_id);
              $session->msg('s',"Articulo agregado al carrito.");
              redirect('add_sale.php', false);
            } else {
              $session->msg('d','Lo siento, registro falló.');
              redirect('add_sale.php', false);
            }
          } else {
             update_product_detail_sale_qty(1,$query[0]['id']);
             $session->msg("s", "Aritulo agregado exitosamente");
             redirect('add_sale.php',false);
          }
      }
          
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <!--
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-info">Búsqueda</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Buscar por el codigo del producto">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
    -->
  </div>
</div>

<div class="row">
  <!----------------------PANEL CARRITO----------------------->
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Venta</span>
            <span class="text-center">
              <?php if (count($open_sale)==1) {
                        echo $open_sale[0]['id'];                 
              } ?>
              
            </span>
            <a href="clean_detail_sale.php?id=<?php echo $open_sale[0]['id']; ?>" class="btn btn-xs btn-danger pull-right" data-toggle="tooltip" title="Borrar">
                  Clean
                </a><span class="pull-right">   </span>
            <a href="open_sale.php?id=<?php echo $user['id'];  ?>" class="btn btn-xs btn-success pull-right" data-toggle="tooltip" title="Nuevo">
                    New 
                </a>    
          </strong>
        </div>

        <div class="panel-body" style="height: 100%;">
          <table class="table table-striped table-bordered table-condensed">
            <thead>
               <tr>
                 <th>Producto</th>
                 <th>Cant.</th>
                 <th>Precio</th>
                 <th>/</th>
               </tr>
            </thead>
            <tbody>
               
                <?php  
                if (count($open_sale)>0):
                
                  $p_adds = find_sale_detail($open_sale[0]['id']); 
                  foreach ($p_adds as $p_add):?>
                    <tr>
                      <td class="" style="font-size:11px; font-weight: bold;"><?php echo $p_add["name"];?></td>
                      <td class="text-center" style="width:20%;"><?php echo $p_add["qty"];?>
                          <a href="add_detail_sale.php?id=<?php echo (int)$p_add['id'];?>" class="btn btn-xs btn-info">+</a>
                          <a href="remove_detail_sale.php?id=<?php echo (int)$p_add['id'];?>" class="btn btn-xs btn-warning">-</a>
                      </td>
                      <td class="text-center"><?php echo number_format(($p_add["price"])*($p_add["qty"]));?></td>
                      <td class="text-center">
                          <a href="delete_detail_sale.php?id=<?php echo (int)$p_add['id'];?>" class="btn btn-xs btn-danger">X</a>
                      </td>
                    </tr>  <?php  
                  endforeach;
                endif ?>
              
            </tbody>
            <thead style="bottom:0;">
               <tr>
                 <th>Total</th>
                 <th></th>
                 <th class="text-center">12345</th>
                 <th></th>
               </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
<!------------------------------------------------------------>
<!----------------------LISTA DE PRODUCTOS------------------------>

    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Lista de Productos</span>
            <div class="col-md-4 pull-right">
              <form method="post" action="" autocomplete="off" id="sug-form">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-btn">
                        <i class="glyphicon glyphicon-th-large"></i>
                      </span>
                      <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Buscar por nombre">
                    </div>
                    <div id="result" class="list-group"></div>
                  </div>
              </form>
            </div>
          </strong>
        </div>


        <div class="panel-body">
          <?php   foreach ($products as $product): ?>
          <div class="col-md-1 shadow-lg" style="width:128px; padding:0;">
            <div class="panel">
              <form method="post" action="add_sale.php?id=<?php echo $product['id']; ?>">

              <button name="add_sale" style="padding:0;border:0">
                <img src="uploads/products/<?php echo $product['image'];?>" class="img-rounded" style="position: relative;width: 100%; height: 160px;" />
                <span class="label label-default" style="position:absolute;right:9px;top: -3px"><?php echo $product['quantity'] ?></span>  <div class="label label-danger" style="position:absolute;left:16px;top:146px">
                    <?php echo '$ '.number_format($product['sale_price'],0); ?>
                </div>
              </button>
              <div class="text-center" style="padding-bottom: 6px; background-color:#EDEBEB;">
                 <strong style="padding-top:10px"><?php echo $product['name'];?></strong><br>
              </div>

              </form>
             </div> 
          </div>
        <?php   endforeach ?>
        </div>


      </div>        
    </div>       
</div>

<?php include_once('layouts/footer.php'); ?>
