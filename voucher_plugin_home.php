<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function custome_voucher_form_data_add(){

       global $wpdb;
       $table_name = $wpdb->prefix.'voucher_details';
       if(isset($_GET['id'])){
        $id = $_GET['id'];
       $oldvoucherlist = $wpdb->get_results( "SELECT * FROM $table_name WHERE `id`='$id'");

         foreach ($oldvoucherlist as  $old_voucher) {
           
           $old_product_id = $old_voucher->product_id;
           $old_product_title = $old_voucher->product_title;
           $old_voucher_code = $old_voucher->voucher_code;

         }

     }
      if (isset($_POST['voucher_update'])) {
       $product_id = $_POST['product_id'];
       $product_title = $_POST['product_title'];
       $voucher_code = $_POST['voucher_code'];
       $update_data = $wpdb->query("UPDATE $table_name SET product_id='$product_id',product_title='$product_title',voucher_code='$voucher_code' WHERE id='$id'");
       if($update_data){
             
          echo "<script>location.replace('admin.php?page=voucher-options');</script>";
          session_start();
          $_SESSION['message']="Voucher list Update Successfully";
       }

     }

	if(isset($_POST['voucher_insert'])){

	global $wpdb;
    $table_name = $wpdb->prefix.'voucher_details';
    // select all data
    //$vouchercode = $_POST['voucher_code'];
    //$voucherlist = $wpdb->get_results( "SELECT COUNT(*) FROM $table_name WHERE `voucher_code`='$vouchercode'",ARRAY_N);
   // $voucherlist = $wpdb->get_results( "SELECT * FROM $table_name WHERE `voucher_code`='$vouchercode'");

    $data = array(
      'product_id'=> htmlspecialchars($_POST['product_id']),
      'product_title'=>htmlspecialchars($_POST['product_title']),
      'voucher_code'=>htmlspecialchars($_POST['voucher_code'])
    );

   $insert =  $wpdb->insert( $table_name, $data);

   if($insert){
       
      $success = "Voucher Code Insrted Successfully";
   }
   else{
   	 $error = "Error inserting";
   }
}
   if(isset($_GET['id'])){
      ?>
 <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="section-title">
                  <h3 class="text-info">Update Voucher List</h3>
               </div>
            </div>
            <div class="col-md-8 offset-md-2">
               <form action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                     <label for="">Product ID</label>
                     <input type="text" name="product_id" id="" class="form-control" value="<?php if(isset($old_product_id)){echo $old_product_id;}?>">
                  </div>
                  <div class="form-group">
                     <label for="">Product Title</label>
                     <input type="text" name="product_title" id="" class="form-control" value="<?php if(isset($old_product_title)){echo $old_product_title;}?>">
                  </div>
                  <div class="form-group">
                     <label for="">Voucher Code</label>
                     <input type="text" name="voucher_code" id="" class="form-control" value="<?php if(isset($old_voucher_code)){echo $old_voucher_code;}?>">
                  </div>
                  <div class="form-group">
                     <input type="submit" value="Update" class="btn btn-success" name="voucher_update"       >
                  </div>
               </form>
            </div>
         </div>
      </div>
   <?php 

   }
   else{
      ?>
 <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="section-title">
                  <h3 class="text-info">Insert Voucher Details Here</h3>
               </div>
            </div>
            <div class="col-md-8 offset-md-2">
               <?php
                  if(isset($success)){
                     ?>
                     <h4 class="text-success">
                       <?php echo $success; ?>
                       <?php unset($success);?>
                     </h4>
                     <?php
                  }
                ?>
                <?php
                  if(isset($error)){
                     ?>
                     <h4 class="text-danger">
                       <?php echo $error; ?>
                       <?php unset($error);?>
                     </h4>
                     <?php
                  }
                ?>
                <?php
                  if(isset($duplicate)){
                     ?>
                     <h4 class="text-danger">
                       <?php echo $duplicate; ?>
                       <?php unset($duplicate);?>
                     </h4>
                     <?php
                  }
                ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                     <label for="">Product ID</label>
                     <input type="text" name="product_id" id="" class="form-control">
                  </div>
                  <div class="form-group">
                     <label for="">Product Title</label>
                     <input type="text" name="product_title" id="" class="form-control">
                  </div>
                  <div class="form-group">
                     <label for="">Voucher Code</label>
                     <input type="text" name="voucher_code" id="" class="form-control">
                  </div>
                  <div class="form-group">
                     <input type="submit" value="Save" class="btn btn-success" name="voucher_insert"       >
                  </div>
               </form>
            </div>
         </div>
      </div>
   <?php  
   }
}
?>