<?php
session_start();
unset($_SESSION['cartProducts']);
unset($_SESSION['cartAddOns']);

header('Location: ../index.php');
