<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function upload_new(){
   global $wpdb;
  	if(isset($_POST['upfile'])){
		if ($_FILES['uploaded']['size'] > 0) { 
			//get the csv file 
			$fext	= $file = $_FILES['uploaded']['name']; 
			$file 	= $_FILES['uploaded']['tmp_name']; 
			$ext 	= pathinfo($fext, PATHINFO_EXTENSION);

			if($ext=='csv'){
				$handle 	= fopen($file,"r"); 
				$table_name = $wpdb->prefix.'voucher_details';
				$wpdb->get_results( "SELECT * FROM `$table_name`");
				$nrows		= $wpdb->num_rows;
				fgetcsv($handle);
				
				if($handle) {
					while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
						if ($data[0] && $data[1]) { 
							$query = $wpdb->insert(
								$table_name,
								array('product_title'=> "$data[0]",'voucher_code'=>"$data[1]",),
							  	array('%s', '%s')         
							);

						} 
					}
					fclose($handle);
				}

				if ($query) {
					$success = "Uploaded successfully";
				}else{
					$error = "Data not uploaded";
				}
			}
			else{
				$error = "You Must Upload Only CSV with .CSV Extension";
			}
		}
  	}
	?>
       <div class="container">
       	<div class="row">
       		<div class="col-md-6">
       			<div class="section-title">
       				<h3>Upload Only CSV Voucher Code here</h3>
       				<?php 
                     if(isset($success)){
                       ?>
                       <h4 class="text-success">
                       	<?php echo  $success; ?>
                       	<?php unset($success) ?>
                       </h4>
                       <?php
                     }
       				?>
       				<?php 
                     if(isset($error)){
                       ?>
                       <h4 class="text-danger">
                       	<?php echo  $error; ?>
                       	<?php unset($error) ?>
                       </h4>
                       <?php
                     }
       				?>
       				<?php 
                     if(isset($olddata)){
                       ?>
                       <h4 class="text-info">
                       	<?php echo  $olddata; ?>
                       	<?php unset($olddata) ?>
                       </h4>
                       <?php
                     }
       				?>
       			</div>
       			<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
       				<div class="form-group">
       					<label for="">Upload CSV File</label>
       					<input name="uploaded" type="file" id="csvfile" class="form-control" />
       				</div>
       				<div class="form-group">
       					<input type="submit" name="upfile" value="Upload File" class="btn btn-success btn">
       				</div>
       			</form>
       		</div>
       		<div class="col-md-4"></div>
       		<div class="col-md-2"></div>
       	</div>
       </div>

	<?php 
}
?>