<html>
    <head>
        <link rel="stylesheet" href="categorias.css">
    </head>
    <body>
    <h3>Categorias</h3>
<?php
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist190617";
        $password = "aaic1560";
        $dbname = $user;
    
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sql = "SELECT * FROM categoria ORDER BY nome";
    
        $result = $db->prepare($sql);
        $result->execute();
    
        echo("<table border=\"0\" cellspacing=\"5\">\n");
        echo("<tr>\n<td class='cabecalho'><p>Categorias</td>\n");
        foreach($result as $row)
        {
            echo("<tr>\n");
            echo("<td>{$row['nome']}</td>\n");

            echo("<td><a href=\"removerConstituicao.php?categoria={$row['nome']}\">Remover Categoria</a><td>\n");
            echo("</tr>\n");

            
        }
        echo("</table>\n");

        echo ('<form action="adicionarSuperCategoria.php" method="post">
            <input type="text" name="nomeSuperCategoria" placeholder="Super Categoria">
            <input type="text" name="nomeSubCategoria" placeholder="Sub-Categoria"> 
            <input type="submit" value="Adicionar Categorias">
            </form>');
    
        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
        