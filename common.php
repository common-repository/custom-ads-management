<?php
    /**
    * Author: Agile Infoways
    * Author URI: http://agileinfoways.com
    */
?>
<?php
    //Global Query
    function getquery($query)
    {
        global $wpdb;  
        $wpdb->query( $wpdb->prepare( $query,""));
    }
    //Get Queries
    function select_data($tablename,$where,$select = '*')
    {
        global $wpdb;  
        $q = "SELECT $select FROM $tablename WHERE $where";    
        $result = $wpdb->get_results($q);
        return $result;
    }
    //Update Queries
    function update_data($tablename,$values,$where)
    {
        global $wpdb;  
        $q = "UPDATE $tablename SET $values WHERE $where";
        getquery($q);
        return ;
    }
    //Insert Queries
    function insert_data($tablename,$fields,$values)
    {
        global $wpdb;  
        $q = "INSERT INTO $tablename ($fields) VALUES($values)";
        getquery($q); 
        return $lastid = $wpdb->insert_id;   
    }
    //Delete Queries
    function delete_data($tablename,$field,$value)
    {
        global $wpdb;  
        if(is_array($value))
            $tags = implode(', ',$value);
        else
            $tags = $value;
        $q = "DELETE FROM $tablename WHERE $field IN(".$tags.")";
        getquery($q);
    }
    
    //This Function is used to upload image
    function uploadImage($data)
    {
        global $wpdb; 
        //Add Custom Options Value when Plugin is been Activated
        $uploadpath = get_option('iwashimages_upload').'/';
        $fileupload = rand().$data['name'];
        $filenme  = $uploadpath.$fileupload;
        move_uploaded_file($data['tmp_name'],$filenme);
        @unlink($file);
        return $fileupload;
    }
?>