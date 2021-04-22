<?php
if(isset($_GET['name'])){
    $name = $_GET['name'];
    echo "Hi $name";
}else{
    echo "Hi Annonymous";
}