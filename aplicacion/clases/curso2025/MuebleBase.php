<?php
require_once '../../../scripts/librerias/validacion.php';
abstract class MuebleBase
{
    public const MATERIALES_POSIBLES = [1 => "Madera", 2 => "Plastico", 3 => "Metal", 4 => "Roca"];
    public const  MAXIMO_MUEBLES = 100;
    private static int $mueblesCreados = 0;
    private string $nombre;
    private string $fabricante;
    private string $pais;
    private DateTime $anio;
    private DateTime $fechaIniVenta;
    private DateTime $fechaFinVenta;
    private string $materialPrincipal;
    private int $precio;

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

    /* GETTERS AND SETTERS*/
    /* Y Setters con validaciones  */

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        if (validaCadena($nombre, 40, "")) {
            return $this->nombre = $nombre;
        } else {
            return false;
        }
    }

    public function getFabricante()
    {
        return $this->fabricante;
    }

    public function setFabricante($fabricante)
    {
        if (strpos($fabricante, 'FMu:') !== 0) {
            $fabricante = 'FMu:' . $fabricante;
        }

        if (validaCadena($fabricante, 30, "FMu:")) {

            return $this->fabricante = $fabricante;
        } else {
            return false;
        }
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function setPais($pais)
    {
        if (validaCadena($pais, 20, "España")) {

            return $this->pais = $pais;
        } else {
            return false;
        }
    }

    public function getAnio()
    {
        return $this->anio;
    }

    public function setAnio($anio)
    {
        if ($anio instanceof DateTime) {
            $this->anio = $anio;
            return $this;
        }

        if (validaFecha($anio, "01/01/2020")) {
            try {
                $this->anio = new DateTime($anio);
                return $this;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getFechaIniVenta()
    {
        return $this->fechaIniVenta;
    }


    public function setFechaIniVenta($fechaIniVenta)
    {
        if ($fechaIniVenta instanceof DateTime) {
            $this->fechaIniVenta = $fechaIniVenta;
            return $this;
        }

        if (validaFecha($fechaIniVenta, "01/01/2020")) {
            try {
                $this->fechaIniVenta = new DateTime($fechaIniVenta);
                return $this;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getFechaFinVenta()
    {
        return $this->fechaFinVenta;
    }

   
    public function setFechaFinVenta($fechaFinVenta)
    {
        if (!isset($this->fechaIniVenta) || !($this->fechaIniVenta instanceof DateTime)) {
            return false;
        }

        if ($fechaFinVenta instanceof DateTime) {
            $fechaFin = $fechaFinVenta;
        } else {
            if (!validaFecha($fechaFinVenta, "31/12/2040")) {
                return false;
            }
            try {
                $fechaFin = new DateTime($fechaFinVenta);
            } catch (Exception $e) {
                return false;
            }
        }

        if ($fechaFin <= $this->fechaIniVenta) {
            return false;
        }

        $this->fechaFinVenta = $fechaFin;
        return $this;
    }

    public function getMaterialPrincipal()
    {
        return $this->materialPrincipal;
    }

    public function setMaterialPrincipal($materialPrincipal)
    {
        $this->materialPrincipal = $materialPrincipal;

        return $this;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /*DESCRIPCCIÓN*/
    public function getMaterialDescripcion()
    {
        return "Nombre: {$this->nombre} , Fabricante: {$this->fabricante} , Pais: {$this->pais} , 
        Año: {$this->anio} ,FechaVenta: {$this->fechaIniVenta}, MaterialPrincipal: {$this->materialPrincipal}  ";
    }


}
