<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Exercițiu PHP</title>
</head>
<body>

<?php
    $message = "Hello din PHP!";

    echo "<h2>Hello World (afișat în browser)</h2>";
    echo "<p>Mesaj: $message</p>";
?>

<script>  
    console.log("<?php echo $message; ?>");
</script>

</body>
</html>