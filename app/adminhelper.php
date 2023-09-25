<?php

/**
 * Helper function to get balance information for the user.
 *
 * @param \App\Models\User $user
 * @param array $sconfig
 * @return array
 */
// app/helpers.php
function getQuery($table, $showType, $fetch = "*", $paging = "N", $debug = "N") {
    $query = "SELECT " . $fetch . " FROM " . $table . " WHERE 1 " . $showType . " ";
    
    if ($debug == 'Y') {
        echo $query;
    }
    
    if ($paging == 'Y') {
        $query = paging_1($query, "", "0%");
    }
    
    // Replace this part with Laravel's query builder or Eloquent
    $result = DB::select($query);
    
    return $result;
}
?>