<?php
//votame uhendust conf.php failist
require("conf.php");

global $yhendus;
//kustutamine - peab olema koodis esimesena
if(isset($_REQUEST['kustuta'])){
    $kask=$yhendus->prepare('DELETE FROM lehed WHERE id=?');
    $kask->bind_param('i', $_REQUEST['kustuta']);
    //i-integer, s-string
    $kask->execute();
}
//tabeli sisu naitamine
//SELECT id, pelkiri, sisu FROM lehed
$kask=$yhendus->prepare('SELECT id, pealkiri, sisu, kuupaev FROM lehed');
$kask->bind_result($id, $pealkiri, $sisu, $kuupaev);
$kask->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tabeli sisu naitamine</title>
</head>
<body>
<h1>Tabeli "lehed" sisu</h1>
<table>
    <tr>
        <th>id</th>
        <th>pealkiri</th>
        <th>sisu</th>
        <th>kuupaev</th>
        <th>kustuta</th>
        <th>dfv</th>
    </tr>
    <?php
    //read tabelist tsukliga
    while ($kask->fetch()) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($id)."</td>";
        echo "<td>".htmlspecialchars($pealkiri)."</td>";
        echo "<td>".htmlspecialchars($sisu)."</td>";
        echo "<td>".htmlspecialchars($kuupaev)."</td>";
        echo "<td><a href='?kustuta=$id'>X</a></td>";
        echo "</tr>";
        echo "<br>";
    }
    ?>
</table>
</body>
</html>
