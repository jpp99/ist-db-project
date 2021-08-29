<html>
    <body>
<?php
    
    $nomeSuperCategoria = $_REQUEST['nomeSuperCategoria'];
    $nomeSubCategoria = $_REQUEST['nomeSubCategoria'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist190617";
        $password = "aaic1560";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        

        $db->beginTransaction();
        
        $linhas_afetadas = $db->exec("UPDATE categoria SET nome='$nomeSuperCategoria' WHERE nome='$nomeSuperCategoria';");
        
        /*super categoria ainda nao existia*/
        if($linhas_afetadas == 0){
            $sql = "INSERT INTO categoria(nome) values ('$nomeSuperCategoria')"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
            $sql = "INSERT INTO super_categoria(nome) values ('$nomeSuperCategoria')"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
        }
        
        $linhas_afetadas = $db->exec("UPDATE categoria_simples SET nome='$nomeSuperCategoria' WHERE nome='$nomeSuperCategoria';");

        /*super categoria era uma categoria simples*/
        if($linhas_afetadas != 0){
            $sql = "DELETE FROM categoria_simples where nome='$nomeSuperCategoria';"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
            $sql = "INSERT INTO super_categoria(nome) values ('$nomeSuperCategoria')"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
            

        }
        
        $linhas_afetadas = $db->exec("UPDATE categoria SET nome='$nomeSubCategoria' WHERE nome='$nomeSubCategoria';");
        
        /*sub categoria nao existia*/
        if($linhas_afetadas == 0){
            $sql = "INSERT INTO categoria(nome) values ('$nomeSubCategoria')"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
            $sql = "INSERT INTO categoria_simples(nome) values ('$nomeSubCategoria')"; 
            $result = $db->prepare($sql);
            $result->execute();
            echo("<p>$sql</p>");
        }

        $sql = "INSERT INTO constituida(super_categoria, categoria) values ('$nomeSuperCategoria', '$nomeSubCategoria')"; 
        $result = $db->prepare($sql);
        $result->execute();
        echo("<p>$sql</p>");
            
    
        $db->commit();
        

        $db = null;

        header("Location:categorias.php");
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
       
    }
?>
    </body>
</html>