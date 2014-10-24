<!DOCTYPE html>
<head>
    <title>BibAdmin | Login</title>
    <link href="./css/loginBox.css" rel="stylesheet" type="text/css" />
    <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>

<script type="text/javascript" src="js/login.js"></script>
<div id="divLoginBox">
<h1>NITH Bibliotek admin</h1>
    <div id="divLoginForm">
        <form method="post" id="formLogin" action="login.php">          
        <table>
        <tr>
        	<td>Brukernavn:</td>
        	<td><input type="text" name="login_username" /></td>
        </tr>
        <tr>
        	<td>Passord:</td>
        	<td><input type="password" name="login_password" /> </td>
        </tr>
        </table>
        </form>
        
    </div>
    <hr />
    <input id="submit" type="submit" value="Logg på" />
    <h2 id="h2Error">Beklager, feil brukernavn/passord</h2>
</div>

</body>
</html>