$(function () {
    cargaChat();
});

function cargaChat() {
    var cookieUsuario = Cookies.get('nombreUsuario');
    if (cookieUsuario != undefined) {
        $('#nombreCookie').text('USUARIO: ' + cookieUsuario);
        $('#nombre').css('display', 'none')
    }
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            document.getElementById('chat').innerHTML = req.responseText;
        }
    }
    req.open('GET', 'chat.php', true);
    req.send();
}

function videollamadaSaliente() {
    /*var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        console.log(req.status);
        if (req.readyState == 4 && req.status == 200) {

        }
    }
    req.open('GET', 'server.php', true);
    req.send();*/
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
            function (vid) {
                bandera = 1;
                //document.querySelector('video').src = window.URL.createObjectURL(vid);
                document.querySelector('video').srcObject = vid;
            },
            function (err) {
                console.log("The following error occured: " + err);
            }
        );
    } else {
        console.log("getUserMedia not supported");
    }

    window.requestAnimFrame = (function (callback) {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function (callback) {
                window.setTimeout(callback, 1000 / 100);
            };
    })();
    window.addEventListener('load', init);
}

setInterval(function () {
    cargaChat();
}, 1000);



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







function video(img) {
    document.querySelector('#miImg').src = img;
}

function init() {
    var canvas = document.querySelector('#miCanvas');
    var video = document.querySelector('video');
    var ctx = canvas.getContext('2d');
    dFrame(ctx, video, canvas);
}

function dFrame(ctx, video, canvas) {
    ctx.drawImage(video, 0, 0, 200, 150);
    var dataURL = canvas.toDataURL('image/jpeg', 0.2);
    if (bandera != 0) {
        send(dataURL); //este es el socket  
    }
    requestAnimFrame(function () {
        setTimeout(function () {
            dFrame(ctx, video, canvas);
        }, 200)
    });
}
