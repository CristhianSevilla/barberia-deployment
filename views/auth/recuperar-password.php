<h1 class="nombre-pagina">Recuperar password</h1>
<p class="descripcion-pagina">Escribe tu nuevo password</p>

<?php

include_once __DIR__ . "/../templates/alertas.php";

?>

<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Password nuevo"
        />
    </div>

    <input type="submit" class="boton" value="Guardar password">
</form>

<div class="acciones">
<a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>