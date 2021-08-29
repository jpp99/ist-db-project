<html>
    <body>
<?php

    $categoria = $_REQUEST['categoria'];
 
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist190617";
        $password = "aaic1560";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        $linhas_afetadas = $db->exec("UPDATE produto SET categoria = '$categoria' WHERE categoria = '$categoria'");

        if($linhas_afetadas >= 1){
            echo("<p>ERRO: Existem produtos com a categoria a eliminar</p>");
        }
        else{

            $sql = "SELECT super_categoria FROM constituida WHERE categoria = '$categoria'"; 
            
            $result = $db->prepare($sql);
            $result->execute();

            /*elimina categoria da tabela constituida*/
            $db->exec("DELETE FROM constituida WHERE super_categoria = '$categoria' or categoria = '$categoria'");
            
            /*verifica as super categorias da categoria removida passam a ser simples*/
            foreach($result as $row)
            {
                $super = $row['super_categoria'];
                
                $linhas_afetadas = $db->exec("UPDATE constituida SET super_categoria = '$super' WHERE super_categoria = '$super'");
                
                if($linhas_afetadas == 0){    
                    $db->exec("DELETE FROM super_categoria WHERE nome = '$super'");
                    $db->exec("INSERT INTO categoria_simples(nome) values ('$super')");  
                }
            }  
            $db->exec("DELETE FROM super_categoria WHERE nome = '$categoria';");  
            $db->exec("DELETE FROM categoria_simples WHERE nome = '$categoria';");  
            $db->exec("DELETE FROM categoria WHERE nome = '$categoria';");
            header("Location:categorias.php");
        }
        $db->commit();
        
        $db = null;

        
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>