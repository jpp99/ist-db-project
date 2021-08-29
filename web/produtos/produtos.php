<html>
    <head>
        <link rel="stylesheet" href="../inserir_remover_produtos/produtos_fornecedores.css">
    </head>
    <body>
    <h3>Produtos</h3>
<?php
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist190617";
        $password = "aaic1560";
        $dbname = $user;
    
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sql = "SELECT ean, design, forn_primario FROM produto";
    
        $result = $db->prepare($sql);
        $result->execute();
    
        echo("<table border=\"0\" cellspacing=\"10\">\n");
        echo("<tr>\n<td class='cabecalho'>EAN</td>\n<td class='cabecalho'>Designa&ccedil;&atilde;o</td>\n</tr>\n");
        foreach($result as $row)
        {
            echo("<tr>\n");
            echo("<td>{$row['ean']}</td>\n");
            echo("<td>{$row['design']}</td>\n");
            echo("<td><a href=\"alterDesign.php?ean={$row['ean']}\">Alterar designacao</a><td>\n");
            echo("</tr>\n");

            
        }
        echo("</table>\n");
    
        $db = null;
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
        