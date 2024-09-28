<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ouvidoria - Tribus Açaí</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
        }

        header {
            background-color: #6200ea;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #6200ea;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #6200ea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #3700b3;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px 0;
            background-color: #f4f4f9;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    <h1>Ouvidoria - Tribus Açaí</h1>
</header>

<div class="container">
    <h2>Deixe sua mensagem</h2>
    <p>A ouvidoria é o canal de comunicação entre você e a Tribus Açaí. Envie sugestões, elogios, reclamações ou dúvidas para que possamos melhorar nossos serviços.</p>

    <form action="ouvidoria_processa.php" method="POST">
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>

        <label for="tipo">Tipo de Mensagem</label>
        <select id="tipo" name="tipo" required>
            <option value="sugestao">Sugestão</option>
            <option value="reclamacao">Reclamação</option>
            <option value="elogio">Elogio</option>
            <option value="duvida">Dúvida</option>
        </select>

        <label for="mensagem">Mensagem</label>
        <textarea id="mensagem" name="mensagem" rows="6" placeholder="Escreva sua mensagem aqui..." required></textarea>

        <button type="submit">Enviar</button>
    </form>
</div>

<footer>
    <p>Tribus Açaí &copy; 2024 - Todos os direitos reservados</p>
</footer>

</body>
</html>
