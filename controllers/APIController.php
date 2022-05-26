<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        //Consultamos la base de datos
        $servicios = Servicio::all();
        //Creamos la API
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar()
    {
        //Almacena la cita y devuelve el ID 
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        //Id de la cita
        $id = $resultado['id'];
        //Id de los servicios
        $idServicios = explode(",", $_POST['servicios']);

        //Almacena la cita y el servicio
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        //Retorna una respuesta
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $cita = Cita::find($id);
            $cita->eliminar();
            //Redirecciona al cliente a la pagina de donde viene
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
