<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php

include_once __DIR__ . "/../templates/alertas.php";

?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">E-mail</label>
        <input
        type="email"
        id="email"
        placeholder="Tu e-mail"
        name="email"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input
        type="password"
        id="password"
        placeholder="Tu password"
        name="password"
        />
    </div>
    <div class="alinear-derecha">
    <input type="submit" class="boton" value="Iniciar Sesión">
    </div>
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>