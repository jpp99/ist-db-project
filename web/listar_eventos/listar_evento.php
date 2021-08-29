<html>
	<body>
    <h3>Listar reposicoes do produto <?=$_REQUEST['ean']?></h3>
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

		/*verifica se existe o produto com o ean pedido*/
		$linhas_afetadas = $db->exec("UPDATE produto SET ean='$ean' WHERE ean='$ean';");

		if($linhas_afetadas == 0){
			echo("<h2>N&atilde;o existe nenhum produto com o ean: {$ean}</h2>");
			echo("<button onclick=\"window.location.href='input_prod.php'\">Voltar</button>");
			return;
		}

		/*verifica se o produto tem alguma reposicao*/
		$linhas_afetadas = $db->exec("UPDATE reposicao SET ean='$ean' WHERE ean='$ean';");

		if($linhas_afetadas == 0){
			echo("<h2>N&atilde;o existe nenhuma reposi&ccedil;&atilde;o do produto com o ean: {$ean}</h2>");
			echo("<button onclick=\"window.location.href='input_prod.php'\">Voltar</button>");
			return;
		}

        $sql = "SELECT ean,operador,instante,unidades FROM reposicao WHERE ean='$ean';";
        
        $result = $db->prepare($sql);

        $result->execute();
        
		echo("<table border=\"0\" cellspacing=\"6\">\n");
		echo("<tr>\n<td>Operador</td>\n<td>Data</td>\n<td>Unidades</td>\n</tr>\n");

		
		foreach($result as $row)
        {
            echo("<tr>\n");
            echo("<td>{$row['operador']}</td>\n");
            echo("<td>{$row['instante']}</td>\n");
            echo("<td>{$row['unidades']}</td>\n");
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
