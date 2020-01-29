<?php

namespace tocketea\app\exceptions;

use Exception;

class UploadException extends Exception
{
    private $fileError;

    public function __construct(int $tipoError)
    {
        $this->fileError = $tipoError;

        switch ($tipoError)
        {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->message = 'El fichero es demasiado grande';
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->message = 'El fichero no se ha podido subir entero';
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->message = 'No se ha podido subir el fichero';
                break;
            default:
                $this->message = 'Error indeterminado.';
        }
    }

    public function getFileError()
    {
        return $this->fileError;
    }
}