<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
session_start();
function all_voucher_list(){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
       global $wpdb;
       $table_name = $wpdb->prefix.'voucher_details';
      $delete = $wpdb->query("DELETE FROM $table_name WHERE id='$id'");
       if($delete){
          $deletesuccess = "Voucher List Delete Successfully";
       }
    }

	?> 
    <script>
   function confirm_delete(){
    return confirm("Are you sure delete this voucher list");
   }
 </script>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12" style="margin-top:50px;">
				<h4>All Course Voucher Lists</h4>
                <h4 class="text-success text-center">
                    <?php 
                    if(isset( $_SESSION['message']))
                        {
                            echo $_SESSION['message'];
                            ?>
                            <script>
                                setTimeout("location.reload(true);",5000);
                            </script>
                            <?php 
                        }
                        unset($_SESSION['message']);
                        ?>
                    </h4>
                <?php 
                  if(isset($deletesuccess)){
                   ?>
                   <h4 class="text-danger text-center">
                    <?php 
                     echo $deletesuccess;
                    ?>
                    <script>
                        setTimeout("location.reload(true);",5000);
                    </script>
                     <?php 
                      ?>
                     <?php unset($deletesuccess);?>
                        
                    </h4>
                   <?php 
                  }
                ?>
    <table id="example" class="hover table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Product Title</th>
                <th>Product Voucher Code</th>
                <th>Edit</th>
                <th>Delete</th>

            </tr>
        </thead>
        <tbody>

        	<?php 
			global $wpdb;
		    $table_name = $wpdb->prefix.'voucher_details';
		    $voucherlist = $wpdb->get_results( "SELECT * FROM $table_name");
        	?>
        	<?php 
               foreach ($voucherlist as $voucher) {
               	 ?>
	             <tr>
	                <td><?php echo $voucher->id; ?></td>
	                <td><?php echo $voucher->product_id;?></td>
	                <td><?php echo $voucher->product_title;?></td>
	                <td><?php echo $voucher->voucher_code;?></td>
                     <td><a href="admin.php?page=Voucher&id=<?php echo $voucher->id;?>" class="btn btn-info btn btn-sm">Edit</a></td>
                     <td><a onclick="return confirm_delete();" href="admin.php?page=voucher-options&id=<?php echo $voucher->id;?>" class="btn btn-danger btn btn-sm">Delete</a></td>
	             </tr>

               	 <?php 
               }
        	?>
        </tbody>
    </table>
			</div>
		</div>
	</div>

	<?php 
}

?>