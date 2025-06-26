<?php
session_start();
session_unset(); // șterge variabilele din sesiune
session_destroy();

header("Location: login.php?success=1");
exit;
?>