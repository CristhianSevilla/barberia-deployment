<h1 class="nombre-pagina">Olvidé mi password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu e-mail</p>

<?php

include_once __DIR__ . "/../templates/alertas.php";

?>

<form action="/olvide" class="formulario" method="POST">
<div class="campo">
        <label for="email">E-mail</label>
        <input
        type="email"
        id="email"
        placeholder="Tu e-mail"
        name="email"
        />
    </div>

    <div class="alinear-derecha">
    <input type="submit" class="boton" value="Enviar instrucciones">
    </div>
</form>

<div class="acciones">
<a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>