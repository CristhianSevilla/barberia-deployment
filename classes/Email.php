<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'ac67e8e7b2dd63';
        $mail->Password = '1e867ace4a2362';

        $mail->setFrom('cuentas@appsalon.com'); //Dominio del proyecto
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        //Usamos html
        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';

        //Creamos cuerpo de email
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>. <br/> <br/>Has creado una cuenta en App Salon, solo debes confirmarla presionando el sigueinte enlace</p>";
        $contenido .= "<p>Presiona aquí:  <a href='https://desolate-fortress-53145.herokuapp.com/confirmar-cuenta?token" . $this->token ."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviamos el email

        $mail->send();
    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'ac67e8e7b2dd63';
        $mail->Password = '1e867ace4a2362';

        $mail->setFrom('cuentas@appsalon.com'); //Dominio del proyecto
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';

        //Usamos html
        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';

        //Creamos cuerpo de email
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>. <br/> <br/>Has solicitado reestablecer tu password, por favor sigue el enlace</p>";
        $contenido .= "<p>Presiona aquí:  <a href='http://localhost:3000/recuperar?token=" . $this->token ."'>Reestablecer password</a></p>";
        $contenido .= "<p>Si tú no solicitaste esto, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviamos el email

        $mail->send();
    }
}
