<?php 
function vouchervalidations(){
    global $wpdb;
    $voucher_code = $_POST["voucher_code"];
    $table_name = $wpdb->prefix.'voucher_details';
    $all_voucher_lists = $wpdb->get_row( "SELECT * FROM $table_name WHERE voucher_code='$voucher_code'");
    $all_voucher_hidden = $wpdb->get_results( "SELECT * FROM $table_name");
    //var_dump($all_voucher_lists);
    if( $all_voucher_lists==1){
        echo $all_voucher_lists->product_title;
    }
    elseif($all_voucher_lists!=1){
        echo 0;
    }
    die();
}
add_action( 'wp_ajax_vouchervalidations', 'vouchervalidations' );
add_action('wp_ajax_nopriv_vouchervalidations', 'vouchervalidations');


add_action( 'gform_after_submission_21', 'post_to_third_party', 10, 2 );
function post_to_third_party( $entry, $form ) {
    global $wpdb;
    $vocuher     = rgpost( 'input_7' );
    $full_name     = rgpost( 'input_1' );
    $email         = rgpost( 'input_4' );
    $course     = rgpost( 'input_6' );
    $password     = '*W2Alpha.SP1#';
    $name         = explode(' ',trim($full_name));
    $username     =  $name[0];
    $f_name     =  $name[0];
    $l_name     =  substr(strstr($full_name," "), 1);
    // echo "<h3> Course Name: ".$course."</h3>";
    //Id search by name
    $args = array("post_type" => "course", "s" => $course);
    $qr = new WP_Query($args);

 
    while ( $qr->have_posts() ) : $qr->the_post();
        $searchPat = get_the_title();
        if (preg_match("~\b$course\b~", $searchPat)) {
            $title = get_the_title();
            $course_id = get_the_ID();
        } else {
           // $course_id = '';
        }
    endwhile;
    wp_reset_postdata();
   // End
    $user_login     =  wp_slash( $username );
    $user_email     =  wp_slash( $email );
    $display_name   =  wp_slash( $full_name );
    $first_name     =  wp_slash( $f_name );
    $last_name      =  wp_slash( $l_name );
    $user_pass      =  $password;
    $role           =  'student';
    $exists = email_exists( $user_email );
    if ( username_exists( $user_login ) && !email_exists( $user_email ) ) {
        $user_login = $user_login.'_student';
    } else {
        $user_login = $user_login;
    }
    if ( !$exists ) {
        $userdata = compact( 'user_login', 'user_email', 'user_pass', 'display_name', 'first_name', 'last_name', 'role' );
        $user_ids = wp_insert_user( $userdata ) ;
		if (wp_insert_user( $userdata )) {
			
			if (wp_new_user_notification($user_ids, false, 'both')) {
			}else{
				echo "Welcome mail has not sent";
			}
			
			$user_id = $user_ids;
		}
    }else{
        $user     = get_user_by( 'email', $user_email );
        $user_id = $user->ID;
    }
    if ( ! is_wp_error( $user_id ) ) {
        $course_check = bp_course_add_user_to_course($user_id,$course_id);
        if ($course_check != '') {
              $table_name = $wpdb->prefix.'voucher_details';
            // $delete     = $wpdb->query("DELETE FROM $table_name WHERE voucher_code='$vocuher'");
        }
    }
}


?>
  <script>
(function($) {
   $(document).ready(function(){
       $("#gform_21 input[type=submit]").attr("disabled", true);
       $('#input_21_6').prop('readonly', true);
       jQuery("#field_21_7").append('<div id="preloader">Loading...</div>');
       jQuery('#gform_21 #preloader').hide();
       $("#gform_21 input[type=submit]").attr("disabled", true);
       $('#input_21_7').on('keyup keypress blur change', function(e) {
           $(".voucher-msg").remove();
           var my_txt = $(this).val();
           var len = my_txt.length;
console.log(len);
           var dataString = 'voucher_code='+ my_txt ;
           if ( len > 9 ){
                   jQuery.ajax({
                       url: "/wp-admin/admin-ajax.php?action=vouchervalidations",
                       type: "POST",
                       data : dataString,
                       beforeSend: function(){
                           jQuery('#gform_21 #preloader').show();
                           $(".voucher-msg").remove();
                       },
                       success:function(data){
                           jQuery('#gform_21 #preloader').hide();
                           if (data == '0') {
                               $(".voucher-msg").remove();
                               $("#field_21_7").append( "<li class='voucher-msg error'><b>Voucher code has not matched.</b></li>" );
                               $("#input_21_6").val('');
                               $("#gform_21 input[type=submit]").attr("disabled", true);
                           }else{
                               $(".voucher-msg").remove();
                               $("#input_21_6").val('');
                               //  $("#field_21_7").append( "<li class='voucher-msg success'><b>Course Name:</b> "+data+"</li>" );
                               $("#field_21_7").append( "<li class='voucher-msg success'><b>Code Matched.</b></li>" );
                               $('#input_21_6').val($('#input_21_6').val() + data);
                               $("#gform_21 input[type=submit]").attr("disabled", false);
                           }
                       },
                   })
           }else{
               $("#input_21_6").val('');
               $("#gform_21 input[type=submit]").attr("disabled", true);
               if ( len > 1 ){
                   $("#field_21_7").append( "<li class='voucher-msg error'><b>Voucher code has not matched.</b></li>" );
               }
           }
       });
   });
})( jQuery );
</script>

<?php 


?>
