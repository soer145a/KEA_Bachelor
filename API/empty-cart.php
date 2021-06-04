<?php
//Undoes the sessions which contains the arrays of what the customer has selected on the frontend
session_start();
unset($_SESSION['cartProducts']);
unset($_SESSION['cartAddOns']);

header('Location: ../index.php');
