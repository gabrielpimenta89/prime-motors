<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/css/login.css">
    <title>Prime Motors</title>
    <link rel="icon" type="image/png" sizes="512x512" href="public/assets/css/favicon.png">

</head>

<body>

    <div class="container">

        <img src="public/assets/css/logoprimemotors-removebg-preview.png"
     alt="Prime Motors"
     class="logo">
      <!--  <form action="../index.php?controller=auth&action=login" method="POST"> -->

      <form method="post" action="/lojadecarros/index.php?controller=auth&action=login">

            <div class="input-box">
                <label>E-mail</label>

                <input type="email" name="email" required>
            </div>

            <div class="input-box">
                <label>Senha</label>

                <input type="password" name="senha" required>
            </div>

            <button type="submit">
                Entrar
            </button>

        </form>

                       <a href="index.php?controller=usuario&action=create" class="btn" style="margin: 3px; text-decoration: none; text-align: center; display: inline-block;">

            Cadastrar
        </a>

    </div>

</body>

</html>