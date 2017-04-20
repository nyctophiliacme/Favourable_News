<?php
	echo "In index.php<br/>";
	$ret = shell_exec("php receiver.php Hello ");
	// > output.txt
	echo "$ret";
	echo "Back";
?>
<script>
	function func(){
		console.log("hello");	
	}
	setInterval(func,1000);

</script>