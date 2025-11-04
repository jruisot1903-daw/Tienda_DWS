<?php
require_once '../../../scripts/librerias/validacion.php';

abstract class MuebleBase
{
    public const MATERIALES_POSIBLES = [1 => "Madera", 2 => "Plastico", 3 => "Metal", 4 => "Roca"];
    public const MAXIMO_MUEBLES = 100;
    private static int $mueblesCreados = 0;

    private string $nombre;
    private string $fabricante;
    private string $pais;
    private DateTime $anio;
    private DateTime $fechaIniVenta;
    private DateTime $fechaFinVenta;
    private int $materialPrincipal;
    private float $precio;

    protected static function incrementarContador(): void
    {
        if (self::$mueblesCreados < self::MAXIMO_MUEBLES) {
            self::$mueblesCreados++;
        } else {
            throw new Exception("Se ha alcanzado el número máximo de muebles permitidos.");
        }
    }

    public static function getMueblesCreados(): int
    {
        return self::$mueblesCreados;
    }

    public static function puedeCrear(&$numero): bool
    {
        $numero = self::MAXIMO_MUEBLES - self::$mueblesCreados;
        return $numero > 0;
    }

    public function __construct($nombre, $fabricante = '', $pais = 'España', $anio = '2020', $fechaIniVenta = '01/01/2020', $fechaFinVenta = '31/12/2040', $materialPrincipal = 1, $precio = 30)
    {
        if (!self::puedeCrear($restantes)) {
            throw new Exception("No se pueden crear más muebles. Límite alcanzado.");
        }

        if (!$this->setNombre($nombre)) {
            throw new Exception("Nombre inválido. No puede estar vacío y debe tener máximo 40 caracteres.");
        }

        $this->setFabricante($fabricante) ?: $this->setFabricante('FMu:');
        $this->setPais($pais) ?: $this->setPais('España');
        $this->setAnio($anio) ?: $this->setAnio('2020');
        $this->setFechaIniVenta($fechaIniVenta) ?: $this->setFechaIniVenta('01/01/2020');
        $this->setFechaFinVenta($fechaFinVenta) ?: $this->setFechaFinVenta('31/12/2040');
        $this->setMaterialPrincipal($materialPrincipal) ?: $this->setMaterialPrincipal(1);
        $this->setPrecio($precio) ?: $this->setPrecio(30);

        self::incrementarContador();
    }

    // Getters y Setters con validaciones
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre)
    {
        if (validaCadena($nombre, 40, "")) {
            $this->nombre = strtoupper($nombre);
            return true;
        }
        return false;
    }

    public function getFabricante() { return $this->fabricante; }
    public function setFabricante($fabricante)
    {
        if (strpos($fabricante, 'FMu:') !== 0) {
            $fabricante = 'FMu:' . $fabricante;
        }
        if (validaCadena($fabricante, 30, "FMu:")) {
            $this->fabricante = $fabricante;
            return true;
        }
        return false;
    }

    public function getPais() { return $this->pais; }
    public function setPais($pais)
    {
        if (validaCadena($pais, 20, "España")) {
            $this->pais = $pais;
            return true;
        }
        return false;
    }

    public function getAnio() { return $this->anio; }
    public function setAnio($anio)
    {
        $anioActual = (int)date('Y');
        if (is_numeric($anio) && $anio >= 2020 && $anio <= $anioActual) {
            $this->anio = new DateTime("01/01/$anio");
            return true;
        }
        return false;
    }

    public function getFechaIniVenta() { return $this->fechaIniVenta; }
    public function setFechaIniVenta($fechaIniVenta)
    {
        try {
            $fecha = new DateTime($fechaIniVenta);
            if ($fecha >= $this->anio) {
                $this->fechaIniVenta = $fecha;
                return true;
            }
        } catch (Exception $e) {}
        return false;
    }

    public function getFechaFinVenta() { return $this->fechaFinVenta; }
    public function setFechaFinVenta($fechaFinVenta)
    {
        try {
            $fecha = new DateTime($fechaFinVenta);
            if (isset($this->fechaIniVenta) && $fecha >= $this->fechaIniVenta) {
                $this->fechaFinVenta = $fecha;
                return true;
            }
        } catch (Exception $e) {}
        return false;
    }

    public function getMaterialPrincipal() { return $this->materialPrincipal; }
    public function setMaterialPrincipal($materialPrincipal)
    {
        if (array_key_exists($materialPrincipal, self::MATERIALES_POSIBLES)) {
            $this->materialPrincipal = $materialPrincipal;
            return true;
        }
        return false;
    }

    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio)
    {
        if (is_numeric($precio) && $precio >= 30) {
            $this->precio = (float)$precio;
            return true;
        }
        return false;
    }

    public function getMaterialDescripcion(): string
    {
        return self::MATERIALES_POSIBLES[$this->materialPrincipal] ?? "Desconocido";
    }

    public function dameListaPropiedades(): array
    {
        return [
            'nombre', 'fabricante', 'pais', 'anio',
            'fechaIniVenta', 'fechaFinVenta',
            'materialPrincipal', 'precio'
        ];
    }

    public function damePropiedad(string $propiedad, int $modo, &$res): bool
    {
        if (!in_array($propiedad, $this->dameListaPropiedades())) {
            return false;
        }

        if ($modo === 1) {
            $metodo = 'get' . ucfirst($propiedad);
            if (method_exists($this, $metodo)) {
                $res = $this->$metodo();
                return true;
            }
        } elseif ($modo === 2) {
            if (property_exists($this, $propiedad)) {
                $res = $this->$propiedad;
                return true;
            } else {
                $metodo = 'get' . ucfirst($propiedad);
                if (method_exists($this, $metodo)) {
                    $res = $this->$metodo();
                    return true;
                }
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return "MUEBLE de clase " . get_class($this) .
            " con nombre {$this->getNombre()}, fabricante {$this->getFabricante()}, fabricado en {$this->getPais()} a partir del año {$this->getAnio()->format('Y')}, vendido desde {$this->getFechaIniVenta()->format('d/m/Y')} hasta {$this->getFechaFinVenta()->format('d/m/Y')}, precio {$this->getPrecio()} de material {$this->getMaterialDescripcion()}";
    }
}
