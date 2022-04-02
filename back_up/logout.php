<?php
session_start();
session_unset();
session_destroy();
session_write_close();
session_regenerate_id(true);
$logout = 1;
if (!empty($_GET['logout'])) $logout = $_GET['logout'];  
header("location:index.php?logout=$logout");
exit;