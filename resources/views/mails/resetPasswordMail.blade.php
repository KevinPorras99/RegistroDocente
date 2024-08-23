<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #2B2D42;
            text-align: center;
            padding: 20px;
            background-color: #F7FAFC;
        }
        .header, .footer {
            background-color: #007bff;
            padding: 20px;
            color: #EDF2F4;
        }
        .header h1, .footer h4 {
            margin: 0;
        }
        .content {
            background-color: #FFFFFF;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .content p {
            color: #000000;
            margin: 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #6C757D;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Team Steam Solutions</h1>
    </div>

    <div class="content">
        <h3>Para restablecer su contraseña, por favor haga clic en el siguiente enlace:</h3>
        <a href="{{ route('password.reset', $token) }}" class="button">Restablecer Contraseña</a>
    </div>

    <div class="content">
        <p>No es necesario responder a este mensaje.</p>
        <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje.</p>
    </div>

    <div class="footer">
        <h4>Derechos Reservados Team Steam Solutions © 2024.</h4>
    </div>
</body>
</html>
