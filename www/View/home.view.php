<?php
if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])){
    echo "<br>";
    echo "Bienvenue ".$_SESSION['email'];
    echo "<br>";
    echo " Role actuel : ".$_SESSION['role'];
}