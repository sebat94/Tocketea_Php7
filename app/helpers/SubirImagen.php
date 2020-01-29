<?php

namespace tocketea\app\helpers;


define('THUMBNAIL_IMAGE_MAX_WIDTH', 660);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 660);

use tocketea\app\exceptions\UploadException;
use Exception;

trait SubirImagen
{
    private $tiposPermitidos;
    private $dirUpload;
    private $nombreCampoFile;
    private $erroresImagenFormulario;

    public function getErroresImagenFormulario()
    {
        return $this->erroresImagenFormulario;
    }

// Movemos el fichero a su nueva ubicación

    /**
     * @return mixed
     */
    public function getTiposPermitidos()
    {
        return $this->tiposPermitidos;
    }

    /**
     * @param mixed $tiposPermitidos
     * @return SubirImagen
     */
    public function setTiposPermitidos($tiposPermitidos)
    {
        $this->tiposPermitidos = $tiposPermitidos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirUpload()
    {
        return $this->dirUpload;
    }

    /**
     * @param mixed $dirUpload
     * @return SubirImagen
     */
    public function setDirUpload($dirUpload)
    {
        $this->dirUpload = rtrim($dirUpload, '/');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreCampoFile()
    {
        return $this->nombreCampoFile;
    }

    /**
     * @param mixed $nombreCampoFile
     * @return SubirImagen
     */
    public function setNombreCampoFile($nombreCampoFile)
    {
        $this->nombreCampoFile = $nombreCampoFile;
        return $this;
    }

    private function compruebaTipo()
    {
        $permitido = false;

        for($i = 0; $i < count($this->tiposPermitidos) && !$permitido; $i++)
        {
            if ($_FILES[$this->nombreCampoFile]['type'] === $this->tiposPermitidos[$i])
                $permitido = true;
        }

        return $permitido;
    }

    private function getNombreImagen()
    {
        $idUnico = time();
        $nombre = $this->dirUpload . '/' . $idUnico.'_' . $_FILES[$this->nombreCampoFile]['name'];

        return $nombre;
    }

    private function muestraImagen(string $nombreImagen)
    {
        $fp = fopen($nombreImagen, 'rb');
        $contenido = fread($fp, filesize($nombreImagen));
        fclose($fp);
    }

    public function subeImagen($devolverImagen = false)
    {

        if ($_FILES[$this->nombreCampoFile]['error'] !== UPLOAD_ERR_OK){
            throw new UploadException($_FILES[$this->nombreCampoFile]['error']);
        }

        $permitido = $this->compruebaTipo();

        if (!$permitido){
            throw new Exception('Error: No se trata de un fichero JPG, JPEG o PNG');
        }


        if (!is_uploaded_file($_FILES[$this->nombreCampoFile]['tmp_name'])){
            throw new Exception('Error: posible ataque. Nombre: '.$_FILES['imagen']['name']);
        }

        $nombreImagen = $this->getNombreImagen();

        if (move_uploaded_file($_FILES[$this->nombreCampoFile]['tmp_name'], $nombreImagen))
        {
            if ($devolverImagen)
            {
                $this->muestraImagen($nombreImagen);
            }
        }
        else
            throw new Exception('Error: No se puede mover el fichero a su destino');


        // Obtenemos la ruta donde pondremos el archivo y le añadimos 'min_' para diferenciarlas de las imagenes normales
        $partesRutaOriginal = explode('/', $nombreImagen);
        if($devolverImagen) // Esta condición nos sirve para saber si la imagen viene del evento o del perfil
            $thumbnail_image_path = 'img/evento/min_' . $partesRutaOriginal[2];
        else
            $thumbnail_image_path = 'img/perfil/min_' . $partesRutaOriginal[2];
        // Creamos la thumbnail
        $this->generate_image_thumbnail($nombreImagen, $thumbnail_image_path);


        return $nombreImagen;
    }


    function generate_image_thumbnail($source_image_path, $thumbnail_image_path)
    {

        if(!file_exists($source_image_path))
            throw new Exception('La imagen '.$source_image_path.' no existe');

        $info = getimagesize($source_image_path);

        if($info === false)
            throw new Exception('No es un archivo válido');

        list($source_image_width, $source_image_height, $source_image_type) = $info;
        $source_gd_image = null;

        switch($source_image_type)
        {
            case 2:
                $source_gd_image = imagecreatefromjpeg ( $source_image_path );
                break;
            case 3:
                $source_gd_image = imagecreatefrompng ( $source_image_path );
                break;
            default:
                throw new Exception('Esto no es un archivo JPG o PNG');
        }

        if ($source_gd_image === false)
        {
            return false;
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;

        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT)
        {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        }
        else if ($thumbnail_aspect_ratio > $source_aspect_ratio)
        {
            $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        }
        else
        {
            $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
        }

        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        $col_transparent = imagecolorallocatealpha($thumbnail_gd_image, 255, 255, 255, 127);
        imagefill($thumbnail_gd_image, 0, 0, $col_transparent);  // set the transparent colour as the background.
        imagecolortransparent ($thumbnail_gd_image, $col_transparent); // actually make it transparent

        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        switch($source_image_type)
        {
            case 2:
                //header( "Content-type: image/jpeg" );
                imagejpeg($thumbnail_gd_image, $thumbnail_image_path);
                break;
            case 3:
                //header( "Content-type: image/png" );
                imagepng($thumbnail_gd_image, $thumbnail_image_path, 9);
                break;
            default:
                throw new Exception('Esto no es un archivo JPG o PNG');
        }

        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return true;

    }

}