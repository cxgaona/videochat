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

        function cargaChat() {
            var cookieUsuario = Cookies.get('nombreUsuario');
            if (cookieUsuario != undefined) {
                $('#nombreCookie').text('USUARIO: ' + cookieUsuario);
                $('#nombre').css('display', 'none')
            }
            var req = new XMLHttpRequest();
            req.onreadystatechange = function() {
                if (req.readyState == 4 && req.status == 200) {
                    document.getElementById('chat').innerHTML = req.responseText;
                }
            }
            req.open('GET', 'chat.php', true);
            req.send();
        }
        setInterval(function() {
            cargaChat();
        }, 1000);
    </script>
    <style>
        .divScroll {
            overflow-y: scroll;
            height: 800px;
        }
    </style>
</head>

<body onload="validacionCookie(),cargaChat(), videollamadaSaliente()">
    <table>
        <tr>
            <td style="vertical-align: top; text-align: center">
                <div>
                    <video autoplay id="video" style="width: 300px"></video>
                    <canvas id='miCanvas'></canvas>
                    <img id='miImg'>

                    <script>
                        
                        function videollamadaSaliente() {
                            var req = new XMLHttpRequest();
                            req.onreadystatechange = function() {
                                console.log(req.status);
                                if (req.readyState == 4 && req.status == 200) {
                                    
                                }
                            }
                            req.open('GET', 'server.php', true);
                            req.send();
                            var bandera = 0;
                            //si el navegador soporta esta caracteristica 

                            window.URL = window.URL || window.webkitURL;
                            navigator.getUserMedia = (navigator.getUserMedia ||
                                navigator.webkitGetUserMedia ||
                                navigator.mozGetUserMedia ||
                                navigator.msGetUserMedia);

                            if (navigator.getUserMedia) //si es compatible
                            {
                                navigator.getUserMedia({
                                        audio: false,
                                        video: true
                                    },
                                    function(vid) {
                                        bandera = 1;
                                        //document.querySelector('video').src = window.URL.createObjectURL(vid);
                                        document.querySelector('video').srcObject = vid;
                                    },
                                    function(err) {
                                        console.log("The following error occured: " + err);
                                    }
                                );
                            } else {
                                console.log("getUserMedia not supported");
                            }

                            window.requestAnimFrame = (function(callback) {
                                return window.requestAnimationFrame ||
                                    window.webkitRequestAnimationFrame ||
                                    window.mozRequestAnimationFrame ||
                                    window.oRequestAnimationFrame ||
                                    window.msRequestAnimationFrame ||
                                    function(callback) {
                                        window.setTimeout(callback, 1000 / 100);
                                    };
                            })();

                            function dFrame(ctx, video, canvas) {
                                ctx.drawImage(video, 0, 0, 200, 150);
                                var dataURL = canvas.toDataURL('image/jpeg', 0.2);
                                if (bandera != 0) {
                                    send(dataURL); //este es el socket  
                                }

                                requestAnimFrame(function() {
                                    setTimeout(function() {
                                        dFrame(ctx, video, canvas);
                                    }, 200)
                                });
                            }
                            window.addEventListener('load', init); //inicia cuando carga la pagina
                            function init() {
                                var canvas = document.querySelector('#miCanvas');
                                var video = document.querySelector('video');
                                var ctx = canvas.getContext('2d');
                                dFrame(ctx, video, canvas);
                            }
                            function video(img)
                                {
                                    document.querySelector('#miImg').src = img;                                    
                                }

                        }
                    </script>



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
<script>

    function abrirCliente() {
        Server = new FancyWebSocket('ws://10.0.0.168:12345');
        console.log('inicio funcion');
        Server.bind('open', function()
        {
        });
        Server.bind('close', function( data ) 
        {
        });
        Server.bind('message', function( payload ) 
        {
        });
        Server.connect();        
        console.log('despues del conect');
    }
    
</script>
<script language="javascript" src="js/jquery-1.7.2.min.js"></script>
<script language="javascript" src="js/fancywebsocket.js"></script>

</html>