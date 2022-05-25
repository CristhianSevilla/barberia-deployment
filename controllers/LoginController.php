<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            //Validar que ingrese un email y un password
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                //Validar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {

                    //verificar que la cuenta de correo este conifirmada y comprobar el password
                    if ($usuario->comprobarPassAndVerific($auth->password)) {

                        if (!isset($_SESSION)) {
                            session_start();
                        };

                        // session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;


                        //Redireccionar al usuario
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado, verifica tu e-mail');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        };

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            //validar que ingrese un email
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    if ($usuario->confirmado) {

                        //Generar un token
                        $usuario->crearToken();
                        $usuario->guardar();

                        //Enviar email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();


                        //Alerta de exito
                        Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu e-mail');
                    } else {
                        Usuario::setAlerta('error', 'Primero confirma tu cuenta, Hemos enviado las instrucciones a tu e-mail');
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe, verifica tu email');
                }
            }

            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {

        $alertas = [];
        $error = false;

        // leemos el token de la ruta
        $token = s($_GET['token']);

        //Buscar al usuario por su token
        $usuario = Usuario::where('token', $token);

        //Verificar que el tiken exista
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo

            $password = new Usuario(($_POST));

            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                
                //Quitamos el password del objeto de usuario
                $usuario->password = null;
                //asigamos el nuevo password al objeto de usuario
                $usuario->password = $password->password;
                //hasheamos el nuevo password
                $usuario->hashearPassword();
                //Borramos el token del objeto del usuario
                $usuario->token= null;
                //Actualizamos datos en la BD
                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }



                debuguear($usuario);
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevacuenta();

            //Verificar que el usuario no este registrado
            if (empty($alertas)) {
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    //el usuario esta registrado
                    $alertas = Usuario::getAlertas();
                } else {
                    //el usuario no esta registrado

                    //hashear password
                    $usuario->hashearPassword();

                    //generar un token
                    $usuario->crearToken();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    //Crear usuario en BD
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {

        $alertas = [];

        //Recuperar token de la url
        $token = s($_GET['token']);

        //Buscar token en BD
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Mostrar Mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //Modificar a usuario confirmado En BD

            // Se confirma
            $usuario->confirmado = "1";
            // Se borra el token para que no pueda volver a ser usado
            $usuario->token = null;
            //Y se guardan los cambios en la BD
            $usuario->guardar();

            $usuario = Usuario::setAlerta('exito', 'Cuenta confirmada exitosamente, ya puedes iniciar sesión');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
