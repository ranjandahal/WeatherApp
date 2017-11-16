<?php
function get_current_day($db) {
    $query = 'SELECT dayNumber FROM systemday';    
    $statement = $db->prepare($query);
    $statement->execute();    
    $currentday = $statement->fetch();
    $statement->closeCursor();    
    $current_day = $currentday['dayNumber'];
    return $current_day;
}

function set_new_day($db, $new_day){
    $query = 'UPDATE systemday SET daynumber=:new_day';    
    $statement = $db->prepare($query);
    $statement->bindValue(':new_day', $new_day);
    $statement->execute();    
    $statement->closeCursor();    
}
?>