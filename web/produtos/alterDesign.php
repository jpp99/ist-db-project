<html>
	<body>
		<h3>ALterar design para o produto <?=$_REQUEST['ean']?></h3>
		<form action="updateDesign.php" method="post">
			<p><input type="hidden" name="ean" value="<?=$_REQUEST['ean']?>"/></p>
			<p>Nova design: <input type="text" name="design"/></p>
			<p><input type="submit" value="Submit"/></p>
		</form>
	</body>
</html>