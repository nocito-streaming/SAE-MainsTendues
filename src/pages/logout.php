<?php
require_once "./src/auth.php";
logout_user();
ob_end_clean();
header('location: index.php?pages=home' );
exit;