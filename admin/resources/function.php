<?php
    function countItem($id , $table , $condition=null){
        global $con ;
        if($condition == "groupid = 0"){
            $stmt = $con->prepare("SELECT COUNT($id) FROM $table WHERE $condition");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
        }else{
            $stmt = $con->prepare("SELECT COUNT($id) FROM $table");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
        }
        
    }
?>