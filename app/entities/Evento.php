<?php

namespace tocketea\app\entities;

use tocketea\app\helpers\SubirImagen;

class Evento
{
    use SubirImagen;

    private $id;
    private $imagen;
    private $titulo;
    private $FK_provincia;
    private $enlace_externo;
    private $FK_categoria;
    private $direccion;
    private $descripcion;
    private $total_entradas;
    private $entradas_restantes;
    private $precio_entradas;
    private $venta_fecha_inicio;
    private $venta_fecha_fin;
    private $fecha_celebracion;
    private $hora_celebracion;
    private $FK_email;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getFKProvincia()
    {
        return $this->FK_provincia;
    }

    /**
     * @param mixed $FK_provincia
     */
    public function setFKProvincia($FK_provincia)
    {
        $this->FK_provincia = $FK_provincia;
    }

    /**
     * @return mixed
     */
    public function getEnlaceExterno()
    {
        return $this->enlace_externo;
    }

    /**
     * @param mixed $enlace_externo
     */
    public function setEnlaceExterno($enlace_externo)
    {
        $this->enlace_externo = $enlace_externo;
    }

    /**
     * @return mixed
     */
    public function getFKCategoria()
    {
        return $this->FK_categoria;
    }

    /**
     * @param mixed $FK_categoria
     */
    public function setFKCategoria($FK_categoria)
    {
        $this->FK_categoria = $FK_categoria;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getTotalEntradas()
    {
        return $this->total_entradas;
    }

    /**
     * @param mixed $total_entradas
     */
    public function setTotalEntradas($total_entradas)
    {
        $this->total_entradas = $total_entradas;
    }

    /**
     * @return mixed
     */
    public function getEntradasRestantes()
    {
        return $this->entradas_restantes;
    }

    /**
     * @param mixed $entradas_restantes
     */
    public function setEntradasRestantes($entradas_restantes)
    {
        $this->entradas_restantes = $entradas_restantes;
    }

    /**
     * @return mixed
     */
    public function getPrecioEntradas()
    {
        return $this->precio_entradas;
    }

    /**
     * @param mixed $precio_entradas
     */
    public function setPrecioEntradas($precio_entradas)
    {
        $this->precio_entradas = $precio_entradas;
    }

    /**
     * @return mixed
     */
    public function getVentaFechaInicio()
    {
        return $this->venta_fecha_inicio;
    }

    /**
     * @param mixed $venta_fecha_inicio
     */
    public function setVentaFechaInicio($venta_fecha_inicio)
    {
        $this->venta_fecha_inicio = $venta_fecha_inicio;
    }

    /**
     * @return mixed
     */
    public function getVentaFechaFin()
    {
        return $this->venta_fecha_fin;
    }

    /**
     * @param mixed $venta_fecha_fin
     */
    public function setVentaFechaFin($venta_fecha_fin)
    {
        $this->venta_fecha_fin = $venta_fecha_fin;
    }

    /**
     * @return mixed
     */
    public function getFechaCelebracion()
    {
        return $this->fecha_celebracion;
    }

    /**
     * @param mixed $fecha_celebracion
     */
    public function setFechaCelebracion($fecha_celebracion)
    {
        $this->fecha_celebracion = $fecha_celebracion;
    }

    /**
     * @return mixed
     */
    public function getHoraCelebracion()
    {
        return $this->hora_celebracion;
    }

    /**
     * @param mixed $hora_celebracion
     */
    public function setHoraCelebracion($hora_celebracion)
    {
        $this->hora_celebracion = $hora_celebracion;
    }

    /**
     * @return mixed
     */
    public function getFKEmail()
    {
        return $this->FK_email;
    }

    /**
     * @param mixed $FK_email
     */
    public function setFKEmail($FK_email)
    {
        $this->FK_email = $FK_email;
    }



}