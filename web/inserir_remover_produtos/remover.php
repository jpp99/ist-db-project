<html>
    <body>
<?php
    
    $ean = $_REQUEST['ean'];
    try
    {
        $host = "db.ist.utl.pt";
        $user ="ist190617";
        $password = "aaic1560";
        $dbname = $user;
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $db->beginTransaction();
        
        
        $sql = "DELETE FROM fornece_sec where ean = :ean;";

         
        
        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean]);

        $sql = "DELETE FROM reposicao WHERE ean = :ean;";
        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean]);

        $sql = "DELETE FROM planograma WHERE ean = :ean;";
        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean]);

        $sql = "DELETE FROM produto WHERE ean = :ean;";
        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean]);

        

        $db->commit();

        $db = null;

        header("Location:produtos_fornecedores.php");
    }
    catch (PDOException $e)
    {
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>