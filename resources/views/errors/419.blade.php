<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Lider Seguros</title>
  <meta content="" name="description">
  <meta content="" name="keywords">



  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">


<style>
  *{
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}

body {
  font-family: "Open Sans", sans-serif;
  color: #fff;

  background-size: cover;
  position: relative;
}

body::before {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: rgba(31, 31, 31, 0.8);
}

@media (min-width: 1366px) {
  body {
    background-attachment: fixed;
  }
}

a {
  color: #ff0000;
  text-decoration: none;
}

a:hover {
  color: #ff3333;
  text-decoration: none;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-family: "Raleway", sans-serif;
}

#main {
  position: relative;
}


/*--------------------------------------------------------------
# Header
--------------------------------------------------------------*/
#header {
  position: relative;
  width: 100%;
  padding: 100px 0;
  /* countdown */
}

#header h1 {
  margin: 0 0 10px 0;
  font-size: 48px;
  font-weight: 700;
  line-height: 56px;
  color: #fff;
}

#header h2 {
  color: #eee;
  margin-bottom: 40px;
  font-size: 22px;
}

#header .countdown {
  margin-bottom: 80px;
}

#header .countdown div {
  text-align: center;
  margin: 10px;
  width: 100px;
  padding: 15px 0;
  background: rgba(255, 255, 255, 0.12);
  border-top: 5px solid #ff0000;
}

#header .countdown div h3 {
  font-weight: 700;
  font-size: 44px;
  margin-bottom: 15px;
}

#header .countdown div h4 {
  font-size: 16px;
  font-weight: 600;
}

@media (max-width: 575px) {
  #header .countdown div {
    width: 70px;
    padding: 10px 0;
    margin: 10px 8px;
  }

  #header .countdown div h3 {
    font-size: 28px;
    margin-bottom: 10px;
  }

  #header .countdown div h4 {
    font-size: 14px;
    font-weight: 500;
  }
}

#header .subscribe {
  font-size: 15px;
  text-align: center;
}

#header .subscribe h4 {
  font-size: 20px;
  font-weight: 600;
  color: #fff;
  position: relative;
  padding-bottom: 12px;
}

#header .subscribe .subscribe-form {
  min-width: 300px;
  margin-top: 10px;
  background: #fff;
  padding: 6px 10px;
  position: relative;
  text-align: left;
}

#header .subscribe .subscribe-form input[type=email] {
  border: 0;
  padding: 4px 8px;
  width: calc(100% - 100px);
}

#header .subscribe .subscribe-form input[type=submit] {
  position: absolute;
  top: 0;
  right: -2px;
  bottom: 0;
  border: 0;
  background: none;
  font-size: 16px;
  padding: 0 20px;
  background: #ff0000;
  color: #fff;
  transition: 0.3s;
  box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
}

#header .subscribe .subscribe-form input[type=submit]:hover {
  background: #f50000;
}

#header .subscribe .error-message {
  display: none;
  color: #ed3c0d;
  text-align: center;
  padding: 15px;
  font-weight: 600;
}

#header .subscribe .sent-message {
  display: none;
  color: #18d26e;
  text-align: center;
  padding: 15px;
  font-weight: 600;
}

#header .subscribe .loading {
  display: none;
  text-align: center;
  padding: 15px;
}

#header .subscribe .loading:before {
  content: "";
  display: inline-block;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  margin: 0 10px -6px 0;
  border: 3px solid #18d26e;
  border-top-color: #eee;
  animation: animate-loading-notify 1s linear infinite;
}

@keyframes animate-loading-notify {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

#header .social-links {
  margin-top: 10px;
}

#header .social-links a {
  font-size: 24px;
  display: inline-block;
  color: rgba(255, 255, 255, 0.8);
  line-height: 1;
  padding-top: 14px;
  margin: 0 10px;
  text-align: center;
  transition: 0.3s;
}

#header .social-links a:hover {
  color: #fff;
}



.cont-temporizador{
    display: flex;
    justify-content: center;
    margin-top: 50px;
}

.cont-temporizador .bloque{
    margin: 0px 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.cont-temporizador .bloque div{
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgb(80, 80, 80);
    box-shadow: 0px 0px 6px 2px #727272 inset;
    color: #ffffff;
    font-size: 40px;
    font-weight: bold;
    width: 100px;
    height: 70px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.cont-temporizador .bloque p{
    font-size: 11px;
    font-weight: bold;
    color: #d6d6d6;
}
.logo{
        width:    9cm;
        height:   2.6cm;


    }
</style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex flex-column align-items-center">
		<div class="logo"> <img src="{{asset('images/lgoo2.png')}}"  height="100%" width="100%" /></div>
      <h1>Tu sesión ha expirado.</h1>
      <h2>Por favor, vuelve a iniciar sesión.</h2>
      <p>Si no eres redirigido automáticamente en <span id="countdown">5</span> segundos...</p> <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        haz clic aquí</a></p>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

      <div class="cont-temporizador">
        <div class="bloque">
          <div class="dias" id="dias">00</div>
          <p>DÍAS</p>
      </div>
      <div class="bloque">
          <div class="horas" id="horas">--</div>
          <p>HORAS</p>
      </div>
      <div class="bloque">
          <div class="minutos" id="minutos">--</div>
          <p>MINUTOS</p>
      </div>
      <div class="bloque">
          <div class="segundos" id="segundos">--</div>
          <p>SEGUNDOS</p>
      </div>



    </div>
  </header><!-- End #header -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>LiderSeguros de Venezuela</span></strong>. Todos los Derechos Reservados
      </div>

    </div>
  </footer><!-- End #footer -->

<script>
  let horas = 0;
let minutos = 0;
let segundos = 05;
cargarSegundo();

//Definimos y ejecutamos los segundos
function cargarSegundo(){
    let txtSegundos;

    if(segundos < 0){
        segundos = 59;
    }

    //Mostrar Segundos en pantalla
    if(segundos < 10){
        txtSegundos = `0${segundos}`;
    }else{
        txtSegundos = segundos;
    }
    document.getElementById('segundos').innerHTML = txtSegundos;
    segundos--;

    cargarMinutos(segundos);
}

//Definimos y ejecutamos los minutos
function cargarMinutos(segundos){
    let txtMinutos;

    if(segundos == -1 && minutos !== 0){
        setTimeout(() =>{
            minutos--;
        },500)
    }else if(segundos == -1 && minutos == 0){
        setTimeout(() =>{
            minutos = 59;
        },500)
    }

    //Mostrar Minutos en pantalla
    if(minutos < 10){
        txtMinutos = `0${minutos}`;
    }else{
        txtMinutos = minutos;
    }
    document.getElementById('minutos').innerHTML = txtMinutos;
    cargarHoras(segundos,minutos);
}

//Definimos y ejecutamos las horas
function cargarHoras(segundos,minutos){
    let txtHoras;

    if(segundos == -1 && minutos == 0 && horas !== 0){
        setTimeout(() =>{
            horas--;
        },500)
    }else if(segundos == -1 && minutos == 0 && horas == 0){
        setTimeout(() =>{
            horas = 2;
        },500)
    }

    //Mostrar Horas en pantalla
    if(horas < 10){
        txtHoras = `0${horas}`;
    }else{
        txtHoras = horas;
    }
    document.getElementById('horas').innerHTML = txtHoras;
}

//Ejecutamos cada segundo
setInterval(cargarSegundo,1000);



        // Contador para mostrar los segundos
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');

        const countdown = setInterval(() => {
            countdownElement.textContent = --seconds;
            if (seconds <= 0) {
                clearInterval(countdown);
                // Realiza el logout automáticamente
                document.getElementById('logout-form').submit();
            }
        }, 1000); // Actualiza cada segundo

</script>

</body>

</html>
