<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu do Gestor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .menu {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .menu h2 {
            text-align: center;
            color: #3A004F;
            margin-bottom: 20px;
        }

        .menu p {
            text-align: center;
            color: #6C757D;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 15px;
        }

        .menu a {
            text-decoration: none;
            color: #3A004F;
            font-weight: bold;
            display: block;
            text-align: center;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: color 0.3s, border-color 0.3s, background-color 0.3s;
        }

        .menu a:hover {
            color: #6B9C00;
            border-color: #6B9C00;
            background-color: #f8f9fa;
        }

        .background-image {
            flex-grow: 1;
            background-image: url('slide 1.jpeg');
            background-size: cover;
            background-position: center;
            filter: brightness(0.8);
        }
    </style>
</head>
<body>
    <div class="menu">
        <h2>Bem-vindo ao Menu do Gestor</h2>
        <p>Aqui você encontrará as opções disponíveis para gestores.</p>
        <ul>
            <li><a href="bancada.php">Bancada</a></li>
			<li><a href="Financeiro.php">Financeiro - Compras e Desepesas</a></li>
			<li><a href="Pedidos.php">Financeiro - Pedidos</a></li>
            <li><a href="Clientes.php">Financeiro - Clientes</a></li>
            <li><a href="index.html">Principal</a></li>
            <li><a href="cadastro.html">Cadastrar Usuários</a></li>
            <li><a href="aprovar_usuarios.php">Aprovar Usuários</a></li>
            <li><a href="OuvidoriaMensagens.php">Fala Açaí</a></li>
        </ul>
    </div>
    <div class="background-image"></div>
</body>
</html>
