<?php
    /**
    * Author: Agile Infoways
    * Author URI: http://agileinfoways.com
    */
?>
<?php
    function customads_menu(){
        $my_plugins_page = add_menu_page('Custom-Ads', 'Custom-Ads', 'manage_options', 'customads-listing', 'customads_listing' );
        add_submenu_page('customads-listing', 'Manage Custom-Ads', 'Manage Custom-Ads', 'manage_options', 'customads-listing','customads_listing' );
        add_submenu_page('customads-listing', 'Help',              'Help',              'manage_options', 'help','help' );
    }

    //This Function will add script/css for admin section
    function customads_adminscripts(){
        wp_enqueue_script( 'customads_script', plugins_url( 'js/customads_js.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_register_style( 'customads_css',    plugins_url('css/customads_css.css', __FILE__ ));
        wp_enqueue_style(  'customads_css' );
    }    

    //This function called when plugin installed
    function customads_install(){
        global $wpdb;
        global $table_customadsdetails;

        $sql_exist = "DROP TABLE IF EXISTS $table_customadsdetails";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_exist );

        $sql = "CREATE TABLE $table_customadsdetails (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customads_title VARCHAR(150),
        customads_short_desc text,
        customads_code text,
        customads_short_code VARCHAR(50),
        customads_status  BOOLEAN,
        customads_created_date  datetime,
        customads_updated_date datetime,
        UNIQUE KEY id (id)
        );";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    //This function called when plugin uninstalled.
    function customads_uninstall(){
        global $wpdb;
        global $table_customadsdetails;

        $sql = "DROP TABLE $table_customadsdetails";       
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $wpdb->query($sql); 
    }
    //This function for database and redirct to link when menu called 
    function customads_listing(){
        global $wpdb;
        global $table_customadsdetails;
        //insert record in database

        if($_POST['original_publish']=='Add'){
            $fields = 'customads_title,customads_short_desc,customads_code,customads_short_code,customads_status,customads_created_date,customads_updated_date';
            $values =  "'".trim(sanitize_text_field($_POST['title']))."','".trim(esc_textarea($_POST['short_desc']))."','".trim($_POST['code'])."','".trim(sanitize_text_field($_POST['short_code']))."','".$_POST['status']."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."'";  
            insert_data($table_customadsdetails,$fields,$values);  
            $message = '<span style="color:green;"><b>Inserted Successfully.</b></span>';
            $_REQUEST['action']='';
        }
        //update record in database
        if($_POST['original_publish']=='Update'){
            $fields = "customads_title = '".trim(sanitize_text_field($_POST['title']))."',customads_short_desc = '".trim(esc_textarea($_POST['short_desc']))."',customads_code = '".trim($_POST['code'])."',customads_status = '".$_POST['status']."',customads_updated_date = '".date("Y-m-d h:i:s")."'";
            $where = 'id = '.$_POST['id'];
            update_data($table_customadsdetails,$fields,$where);                                                                                                                                         
            $message = '<span style="color:green;"><b>Updated Successfully.</b></span>';    
            $_REQUEST['action']='';
        }
        //delete record from database
        if($_REQUEST['action']=='delete' || $_REQUEST['action2']=='delete'){                                                          
            if($_REQUEST['id']!=''){
                delete_data($table_customadsdetails,'id',$_REQUEST['id']);
                $message = '<span style="color:red;"><b>Deleted Successfully.</b></span>';
            }
            else if(count($_REQUEST['item'])>0){
                    delete_data($table_customadsdetails,'id',$_REQUEST['item']);
                    $message = '<span style="color:red;"><b>Deleted Successfully.</b></span>';
                }
                else{
                    $message = '<span style="color:red;"><b>No Data Found.</b></span>';
            }
            $_REQUEST['action']='';
        }
        //redirect to create new add page
        if($_REQUEST['action']=='add' || $_REQUEST['action']=='edit'  ){
            include('admin/edit_customads.php');
        }
        else{
            include('admin/ads_inventory_details.php');
        }
    }

    function help(){
        include('admin/help.php');
    }

    //Shortcode Start Here
    function Customads($content=null){
        global $wpdb;
        global $table_customadsdetails;
        global $shortcode_tags;        
        $customadsList = $wpdb->get_row("SELECT customads_code FROM ".$table_customadsdetails ." WHERE customads_status='1' AND customads_short_code  ='[Customads id=".$content['id']."]'", ARRAY_A);
        $data = '';
        $ext= explode(".",$customadsList['customads_code']); 
        if(end($ext)=='png' || end($ext)=='jpg' || end($ext)=='jpeg' || end($ext)=='gif'){
            $data.='<div id="customads"><img src='.esc_url($customadsList['customads_code']).'/></div>';    
        }else{
            $data.='<div id="customads">'.$customadsList['customads_code'].'</div>';
        }
        return $data;
    }
    //Shortcode End Here 
?>