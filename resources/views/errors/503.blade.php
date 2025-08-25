<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lider de Seguros - Mantenimiento</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Raleway:300,400,600,700" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Open Sans", sans-serif;
      color: #fff;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset("images/background2.jpg") }}') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    #header {
      text-align: center;
      padding: 60px 20px;
      background: rgba(0, 0, 0, 0.541);
      border-radius: 10px;
      width: 100%;
      max-width: 600px;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.575);
    }

    .logo img {
      width: 350px; /* Increased size for better visibility */
    }

    h1 {
      font-size: 48px;
      font-weight: 700;
      color: #f5f5f5;
      margin-bottom: 20px;
    }

    h2 {
      color: #ddd;
      font-size: 20px;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header id="header">
    <div class="logo">
      <img src="{{ asset('images/logo.png') }}" alt="Lider de Seguros">
    </div>
    <h1>Página en Mantenimiento</h1>
    <h2>Estamos trabajando para mejorar la experiencia.</h2>
  </header>

</body>

</html>
