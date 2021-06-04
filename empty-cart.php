<?php
session_start();
unset($_SESSION['cartAddOns']);
unset($_SESSION['cartProducts']);
header('Location: index.php');
