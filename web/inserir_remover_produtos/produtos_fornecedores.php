<html>
    <head>
        <link rel="stylesheet" href="produtos_fornecedores.css">
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
            echo("<td>{$row['forn_prim']}</td>\n");
            echo("<td>{$row['forn_sec']}</td>\n");
            echo("<td><a href=\"remover.php?ean={$row['ean']}\">Remover Produto</a><td>\n");
            echo("</tr>\n");

            
        }
        echo("</table>\n");

        echo ('<p>Adicionar produto:</p>');
        echo ('<form action="inserir.php" method="post">
            <input type="text" name="ean" placeholder="ean do produto">
            <input type="text" name="nome_produto" placeholder="nome do produto">
            <input type="text" name="nif_fornecedor_primario" placeholder="nif do fornecedor primario">
            <input type="text" name="nif_fornecedor_secundario" placeholder="nif do fornecedor secundario">
            <input type="text" name="categoria" placeholder="categoria">
            <input type="submit" value="Adicionar Produto">
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
        