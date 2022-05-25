<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Crear Nuevo Servicio</p>

<?php
include __DIR__ . '/../templates/barra.php';
include __DIR__ . '/../templates/alertas.php';
?>

<p class="descripcion-pagina">LLena los campos del formulario</p>

<form action="/servicios/crear" class="formulario" method="POST">

    <?php
    include_once __DIR__ . '/formulario.php';
    ?>

    <div class="alinear-derecha">
        <input type="submit" class="boton" value="Guardar Servicio">
    </div>
</form>