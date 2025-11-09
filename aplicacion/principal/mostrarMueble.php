<?php
require_once dirname(__FILE__) . "/../clases/curso2025/Muebles.php";
require_once dirname(__FILE__) . "/../clases/curso2025/MuebleBase.php";

$id = $_GET['idMueble'] ?? null;

if (!is_numeric($id) || !isset($muebles[$id])) {
    echo "<p>Mueble no válido.</p>";
    echo '<a href="index.php">Volver</a>';
    exit;
}

$mueble = $muebles[$id];
$propiedades = $mueble->dameListaPropiedades();

echo "<h2>Propiedades del mueble #$id (" . get_class($mueble) . ")</h2>";
echo "<ul>";
foreach ($propiedades as $prop) {
    $valor = null;
    if ($mueble->damePropiedad($prop, 1, $valor)) {
        if ($valor instanceof DateTime) {
            $valor = $valor->format('d/m/Y');
        }
        echo "<li><strong>$prop:</strong> " . htmlspecialchars((string)$valor) . "</li>";
    }
}
echo "</ul>";
echo '<a href="/aplicacion/principal/index.php">Volver</a>';
