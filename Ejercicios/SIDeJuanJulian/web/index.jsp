<%-- 
    Document   : index
    Created on : Oct 20, 2014, 11:42:19 AM
    Author     : gjulianm
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Sistemas informáticos</title>
    </head>
    <body>
        <h1>Correo y contraseña</h1>
        <form action="process">
            <label for="email">Correo: </label>
            <input type="text" id="email" name="email" /><br />
            
            <label for="pass">Contraseña: </label>
            <input type="password" id="password" name="pass" /><br />
            
            <input type="submit" value="Enviar" />
        </form>
    </body>
</html>
