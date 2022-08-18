<?php
  $page_title = 'Lista de productos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  //$products = join_product_table();
  $all_categories = find_all('categories');
?>
<?php
// ------------------- BUSCADOR ----------------
$connection=new mysqli("localhost","root","","prueba");
$products=array();
$product_title="";
$product_categorie="";
$discriminante=0;
if (isset($_POST["search_product"])) {
  $product_title=$_POST["product-title"];
  $product_categorie=$_POST["product-categorie"];

  if ($product_title!="" or $product_categorie!="") {
      $query = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.categorie_id,p.media_id, c.name";
      $query.=" AS categorie, m.file_name AS image";
      $query.=" FROM products p";
      $query.=" LEFT JOIN categories c ON c.id = p.categorie_id";
      $query.=" LEFT JOIN media m ON m.id = p.media_id";
      $query.=" WHERE p.name LIKE '%".$product_title."%' AND p.categorie_id LIKE '%".$product_categorie."%' ORDER BY id ASC ";
      $resultado = mysqli_query($connection,$query);
      $discriminante=1;
    }

  }
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Productos</span>
          </strong>
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-info">Agregar producto</a>
         </div>
        </div>
        <div class="panel-body">

<!-------------------CAJA--BUSQUEDA------------------------------------------------------------------>        
          <form method="POST" action="product.php" class="clearfix">

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Nombre Producto">
               </div>
              </div>

              <div class="form-group">
                  <div class="row">

                      <div class="col-md-6">
                        <select class="form-control" name="product-categorie">
                          <option value="">Selecciona una categoría</option>
                        <?php  foreach ($all_categories as $cat): ?>
                          <option value="<?php echo (int)$cat['id'] ?>">
                            <?php echo $cat['name'] ?></option>
                        <?php endforeach; ?>
                        </select>
                      </div>

                  </div>
              </div>
  
              <button type="submit" name="search_product" class="btn btn-danger">Buscar</button>
          </form><br>

<!---------------------------------------------------------------------------------------------------->          
          <table class="table table-bordered table-striped">


            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Imagen</th>
                <th> Nombre </th>
                <th class="text-center" style="width: 10%;"> Categoria </th>
                <th class="text-center" style="width: 10%;"> Stock </th>
                <th class="text-center" style="width: 10%;"> Precio Compra </th>
                <th class="text-center" style="width: 10%;"> Precio Venta </th>
                <th class="text-center" style="width: 100px;"> Acciones </th>
              </tr>
            </thead>


            <tbody>
              <?php if ($discriminante==1):?>
              <?php while ($mostrar = mysqli_fetch_array($resultado)){?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($mostrar['image'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $mostrar['image']; ?>" alt="">
                <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($mostrar['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($mostrar['categorie']); ?></td>
                <td class="text-center"> <?php echo remove_junk($mostrar['quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($mostrar['buy_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($mostrar['sale_price']); ?></td>
                <td class="text-center">

                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$mostrar['id'];?>" class="btn btn-warning btn-xs"  title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                     <a href="delete_product.php?id=<?php echo (int)$mostrar['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>

                </td>
              </tr>
             <?php } ?>
           <?php endif; ?>
            </tbody>


          </table>

        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
