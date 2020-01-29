<?php

namespace tocketea\app\entities;

class Mensaje
{

    private $id;
    private $FK_grupo;
    private $enviado_por;
    private $recibido_por;
    private $titulo;
    private $descripcion;
    private $fecha_hora;

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
    public function getFKGrupo()
    {
        return $this->FK_grupo;
    }

    /**
     * @param mixed $FK_grupo
     */
    public function setFKGrupo($FK_grupo)
    {
        $this->FK_grupo = $FK_grupo;
    }

    /**
     * @return mixed
     */
    public function getEnviadoPor()
    {
        return $this->enviado_por;
    }

    /**
     * @param mixed $enviado_por
     */
    public function setEnviadoPor($enviado_por)
    {
        $this->enviado_por = $enviado_por;
    }

    /**
     * @return mixed
     */
    public function getRecibidoPor()
    {
        return $this->recibido_por;
    }

    /**
     * @param mixed $recibido_por
     */
    public function setRecibidoPor($recibido_por)
    {
        $this->recibido_por = $recibido_por;
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
    public function getFechaHora()
    {
        return $this->fecha_hora;
    }

    /**
     * @param mixed $fecha_hora
     */
    public function setFechaHora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
    }

}