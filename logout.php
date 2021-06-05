<?php
//Start the session, and the destroy it and redirect the user to the index page
session_start();
session_destroy();
header("Location: index.php");
exit();
