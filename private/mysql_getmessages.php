<?php
function get_messages($bddco, $table, $where, $where_is, $order_by){
    $response = $bddco->query('SELECT * FROM ' . $table . ' WHERE ' . $where . '=\'' . $where_is . '\' ORDER BY ' . $order_by);
    return $response;
}
?>