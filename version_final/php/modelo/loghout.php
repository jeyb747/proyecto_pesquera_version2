<?php
session_start();
session_destroy();

header("Location: /version_final/index.php");
exit();
?>