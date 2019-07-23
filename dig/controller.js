$(function () {
    cargaChat();
    if (cookieUsuario != undefined) {        
        $('#pantallas').append('<img style="width: 99%;" id="img-'+cookieUsuario+'" >');
    }    
});

function cargaChat() {
    cookieUsuario = Cookies.get('nombreUsuario');
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
    $('#streaming').css('display','block')       
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
    document.querySelector('#img-'+cookieUsuario).src = img;
    //document.getElementById('texto').value = img;
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
