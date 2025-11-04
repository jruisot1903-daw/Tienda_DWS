<?php
include_once(dirname(__FILE__) . "/cabecera.php");
//Controlador


//Dibuja la plantilla de la vista 
inicioCabecera("2DAW Ejercicios");
cabecera();
finCabecera();

inicioCuerpo("EJERCICIOS");
cuerpo(); //llamo a la vista
finCuerpo();



// **********************************************************

//Vista
function cabecera() {}

//Vista
function cuerpo()
{
?>
    Hola estas en index.php 
<?php

}
