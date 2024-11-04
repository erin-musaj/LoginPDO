<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Result</title>
</head>

<body>

</body>

</html>
<?php

$username = $_POST["username"];
$password = $_POST["password"];

if ($username != "root" || $password != "") {
    echo "Access denied";
} else {
    echo "Access allowed<br>";

    $host = 'localhost';
    $db = '5ina';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password, ); //crea la connessione
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //serve per trovare errori di connessione
        echo "Connection OK<br>";
    } catch (PDOException $e) {
        echo "Connection error<br>";
    }

    $queries = array(
        "SELECT COUNT(*) FROM puntoprelievi;",
        "SELECT Comune, COUNT(*) FROM puntoprelievi GROUP BY Comune;",
        "SELECT MAX(Latitudine), MIN(Latitudine), MAX(Logitudine), MIN(Logitudine) FROM puntoprelievi;",
        "SELECT Comune, MAX(Latitudine) FROM puntoprelievi;",
        "SELECT AVG(Latitudine) FROM puntoprelievi;",
        "SELECT Comune, AVG(Latitudine) FROM puntoprelievi GROUP BY Comune;",
        "SELECT SUM(Logitudine) FROM puntoprelievi;",
        "SELECT COUNT(*) FROM puntoprelievi WHERE note IS NOT NULL;",
        "SELECT MAX(Latitudine) FROM puntoprelievi WHERE Comune = 'Ala';",
        "SELECT Comune, COUNT(*) FROM puntoprelievi GROUP BY Comune ORDER BY COUNT(*) DESC;",
        "SELECT Comune, AVG(Logitudine) FROM puntoprelievi GROUP BY Comune HAVING COUNT(*)>3;"
    );

    $fields = array(
        "idPrel",
        "Comune",
        "Indirizzo",
        "Telefono",
        "note",
        "Latitudine",
        "Logitudine"
    );

    for ($j = 0; $j < count($queries); $j = $j + 1) {
        $result = $conn->prepare($queries[$j]); //prepara la query
        $result->execute(); //esegue la query
        echo "<br>$queries[$j]<br>";
        echo "<table>";
        while ($row = $result->fetch(PDO::FETCH_NUM /*scorre le occorrenze con valore numerico*/)) {
            echo "<tr>";
            for ($i = 0; $i < count($row); $i = $i + 1) {
                echo "<td>" . $row[$i] . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    $conn = null; //chiude la connessione
}

?>