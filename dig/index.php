<?php
include "db.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title> CHAT </title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link href="https://fonts.googleapis.com/css?family=Mukta+Vaani&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="controller.js"></script>
    <style>
        .divScroll {
            overflow-y: scroll;
            height: 800px;
        }
    </style>

    <script type="text/javascript">
        function validacionCookie() {
            /*var cookieVideollamada = Cookies.get('videollamada');
            if (cookieVideollamada != undefined) {
                videollamadaSaliente()
            }*/
        }

        function video(img) {
            document.querySelector('#miImg').src = img;
            document.getElementById('texto').value = img;
        }
    </script>

</head>

<body>
    <table>
        <tr>
            <td style="vertical-align: top; text-align: center">
                <div>
                    <video autoplay id="video" style="width: 300px"></video>
                    <canvas id='miCanvas'></canvas>
                    <img id='miImg'>                   
                </div>
            </td>
            <td>
                <div id="contenedor">
                    <div id="caja-chat" class="divScroll">
                        <div id="chat"></div>
                    </div>
                    <form method="POST" action="index.php" enctype="multipart/form-data">
                        <input id="nombre" type="text" name="nombre" placeholder="Ingresa tu nombre">
                        <span id="nombreCookie"></span>
                        <textarea name="mensaje" placeholder="Ingresa el mensaje"></textarea>
                        <input type="file" name="archivo" />
                        <input type="submit" name="enviar" value="Enviar">
                        <input type="submit" name="borrarChat" value="Limpiar Chat">
                        <input type="button" id="videollamada" onclick="videollamadaSaliente()" value="Videollamada">
                        <input type="button" id="no se" onclick="abrirCliente()" value="Tu madre">
                    </form>
                    <?php
                    if (isset($_POST['enviar'])) {
                        $nombre = "";
                        if (isset($_COOKIE["nombreUsuario"])) {
                            $nombre = $_COOKIE['nombreUsuario'];
                        } else {
                            $nombre = $_POST['nombre'];
                        }
                        $mensaje = $_POST['mensaje'];
                        $archivo = "";
                        $consulta = "";
                        if (!empty($_FILES['archivo']['name'])) {
                            copy($_FILES['archivo']['tmp_name'], 'archivosEnviados/' . date('Ymdhis') . $_FILES['archivo']['name']);
                            $archivo = 'archivosEnviados/' . date('Ymdhis') . $_FILES['archivo']['name'];
                        }

                        if (empty($_POST['mensaje']) && empty($_FILES['archivo']['name'])) {
                            echo "<script>alert('No puedes enviar mensajes vacíos')</script>";
                        } else {
                            $consulta = "INSERT INTO chat (nombre, mensaje,url_archivo) VALUES ('$nombre' ,'$mensaje','$archivo')";
                            $ejecutar = $conexion->query($consulta);
                            if ($ejecutar) {
                                echo "<embed loop = 'false' src='beep.mp3' hidden='true' autoplay='true'> ";
                            }
                            echo "<script>Cookies.set('nombreUsuario','" . $nombre . "');</script>";
                        }
                    }
                    if (isset($_POST['borrarChat'])) {
                        $consulta = "TRUNCATE TABLE CHAT";
                        $ejecutar = $conexion->query($consulta);
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
</body>

<script language="javascript" src="js/jquery-1.7.2.min.js"></script>
<script language="javascript" src="js/fancywebsocket.js"></script>

</html>