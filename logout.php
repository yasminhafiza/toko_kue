<?php
echo "Logging out...";  // Jangan lakukan ini sebelum header
session_start();
session_unset();
session_destroy();
header('Location: landing.php');
exit();
?>
