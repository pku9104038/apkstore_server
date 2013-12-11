<?php
if (!isset($session_name)){
    $session_name = 'tmp';
}

$order_name = $session_name.'_order';
$sort_name = $session_name.'_sort';
$page_name = $session_name.'_page';

if (!isset($_GET['order'])){
    if (empty($_SESSION["$order_name"])){
        $order = 0;
    }
    else{
        $order = $_SESSION["$order_name"];
    }
}
else{
    $order = $_GET['order'] + 0;
}
$reorder = 1-$order;

if(!isset($_GET['sort'])){
    if (empty($_SESSION["$sort_name"])){
        ;
    }
    else{
        $sort = $_SESSION["$sort_name"];
    }
}
else{
    $sort = $_GET['sort'];
}

if(!isset($_GET['page'])){
    if(empty($_SESSION["$page_name"])){
        $cur_page = 1;
    }
    else{
        $cur_page = $_SESSION["$page_name"];
    }
}
else {
    $cur_page = $_GET['page'];
}
$_SESSION["$sort_name"] = $sort;
$_SESSION["$page_name"] = $cur_page;
$_SESSION["$order_name"] = $order;

?>