<?php 

    $xml = simplexml_load_file('../conf/app_conf.xml');
    $json = json_encode($xml);
    $obj = json_decode($json);
        
    $app_copyright = $obj->APP_COPYRIGHT;  

    
    $_SESSION['back_page_name'] = $page_title;
    $_SESSION['back_page_link'] = $_SERVER ['PHP_SELF'];
    
    
?>

  <hr />
  <p class="footer">Copyright &copy; <?php echo $app_copyright; ?></p>
</body>
</html>
