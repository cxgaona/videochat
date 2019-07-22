<?php

include "db.php";

    $consulta = "SELECT * FROM  chat ORDER BY id ASC";
    $ejecutar = $conexion->query($consulta);
    while($fila = $ejecutar->fetch_array()){
    ?>
    <div id="datos-chat">
    <span style="color: #1c62c4;"><?php echo $fila['nombre'];?> </span>
    <span style="color: #848484;"><?php echo $fila['mensaje'];?></span>
    <span style="float: right;"><?php echo  ($fila['fecha']);?></span>
    <a href="<?php echo  ($fila['url_archivo']);?>" target="_blank"><?php echo  ($fila['url_archivo']);?></a>    
    
</div>
<?php }; ?>

