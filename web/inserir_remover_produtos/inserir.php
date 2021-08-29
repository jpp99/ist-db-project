<html>
    <body>
<?php
    
    $ean = $_REQUEST['ean'];
    $nome_produto = $_REQUEST['nome_produto'];
    $fornecedor_primario = $_REQUEST['nif_fornecedor_primario'];
    $fornecedor_secundario = $_REQUEST['nif_fornecedor_secundario'];
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
        $sql = "INSERT INTO produto(ean, design, forn_primario, categoria, data_op) values(:ean, :nome_produto, :fornecedor_primario, :categoria, :data_op);";
         
        
        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean, ':nome_produto' => $nome_produto, ':fornecedor_primario' => $fornecedor_primario, ':categoria' => $categoria, ':data_op' =>  date('Y-m-d')]);

        $sql = "INSERT INTO fornece_sec(nif,ean) values (:fornecedor_secundario, :ean);";

        echo("<p>$sql</p>");

        $result = $db->prepare($sql);
        $result->execute([':ean' => $ean, ':fornecedor_secundario' => $fornecedor_secundario]);

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