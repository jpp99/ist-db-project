<html>
	<body>
<?php
	$ean = $_REQUEST['ean'];
	$design = $_REQUEST['design'];
	try
	{
		$host = "db.ist.utl.pt";
		$user ="ist190617";
		$password = "aaic1560";
		$dbname = $user;
		$db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE produto SET design = :design WHERE ean = :ean;";

		echo("<p>$sql</p>");
		$result = $db->prepare($sql);
		$result->execute([':design' => $design, ':ean' => $ean]);
		$db = null;
	}
	catch (PDOException $e)
	{
		echo("<p>ERROR: {$e->getMessage()}</p>");
	}
?>
	</body>
</html>