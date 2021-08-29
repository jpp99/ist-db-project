<html>
	<head>
		<link rel="stylesheet" href="listarSub.css">
	</head>

	<body>
<?php
    $super_categoria = $_REQUEST['super_categoria'];
	try
	{
		$host = "db.ist.utl.pt";
		$user ="ist190617";
		$password = "aaic1560";
		$dbname = $user;
		$db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$linhas_afetadas = $db->exec("UPDATE super_categoria SET nome='$super_categoria' WHERE nome='$super_categoria';");

		if($linhas_afetadas == 0){
			echo("<h2>N&atilde;o existe nenhuma Super Categoria com o nome: {$super_categoria}</h2>");
			echo("<button onclick=\"window.location.href='pede_super.php'\">Voltar</button>");
			return;
		}
		/*query que resulta numa tabela com todas as sub-categorias (em toda a profundidade)*/
		$sql = "with recursive tree as(
						select super_categoria, categoria
						from constituida
						where super_categoria = :super_categoria
						union all
						select child.super_categoria, child.categoria
						from constituida as child
						join tree as parent on parent.categoria = child.super_categoria
						)
						select *
						from tree";
        
		$result = $db->prepare($sql);

		$result->execute([':super_categoria' => $super_categoria]);

		
		echo("<table border=\"0\" cellspacing=\"10\">\n");
		
		echo("<h1>SubCategorias de {$super_categoria}</h1>");
		
		foreach($result as $row){
			echo("<tr>\n");
			echo("<td><p>{$row['categoria']}</p></td>\n");
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
