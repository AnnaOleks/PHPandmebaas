<?php
//votame uhendust conf.php failist
require("conf.php");

//tabeli sisu naitamine
//select id, pealkiri, sisu from lehed
global $yhendus;
//kustutamine peab olema koodis esimesena
if (isset($_REQUEST['kustuta'])){
    $kask=$yhendus->prepare('DELETE FROM lehed WHERE id=?');
    $kask->bind_param('i', $_REQUEST['kustuta']);
    //i-integer, s-string
    $kask->execute();
}

if(isSet($_REQUEST["uusleht"])){
    $kask=$yhendus->prepare("INSERT INTO lehed (pealkiri, sisu, kuupaev) VALUES (?, ?, ?)");
    $kask->bind_param("sss", $_REQUEST["pealkiri"], $_REQUEST["sisu"], $_REQUEST["kuupaev"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

$kask=$yhendus->prepare('SELECT id, pealkiri, sisu, kuupaev FROM lehed');
$kask->bind_result($id, $pealkiri, $sisu, $kuupaev);
$kask->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tabeli sisu näitamine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <br>
    <h1>Tabeli "lehed" sisu</h1>
    <br>
    <table id="menyykiht">
        <tr>
            <th>id</th>
            <th>pealkiri</th>
            <th>sisu</th>
            <th>kuupäev</th>
            <th>kustuta</th>
        </tr>

        <?php
        //read tabelist tsukliga
        while ($kask->fetch()) {
            echo "<tr>";
            echo "<td>".htmlspecialchars($id)."</td>";
            echo "<td>".htmlspecialchars($pealkiri)."</td>";
            echo "<td>".htmlspecialchars($sisu)."</td>";
            echo "<td>".htmlspecialchars($kuupaev)."</td>";
            echo "<td><a href='?kustuta=$id'><strong>X</strong></a></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <br>
    <a href='<?="$_SERVER[PHP_SELF]?lisamine=jah" ?>'>Lisa</a>

    <div id="sisukiht">
        <?php
        if(isSet($_REQUEST["id"])){
            $kask=$yhendus->prepare("SELECT id, pealkiri, sisu, kuupaev FROM lehed WHERE id=?");
            $kask->bind_param("i", $_REQUEST["id"]);
            $kask->bind_result($id, $pealkiri, $sisu, $kuupaev);
            $kask->execute();
            if($kask->fetch()){
                echo "<h2>".htmlspecialchars($pealkiri)."</h2>";
                echo htmlspecialchars($sisu);
                echo "<h2>".htmlspecialchars($kuupaev)."</h2>";
                echo "<br /><a href='$_SERVER[PHP_SELF]?kustutusid=$id'>kustuta</a>";
            } else {
                echo "Vigased andmed.";
            }
        }
        if(isSet($_REQUEST["lisamine"])){
            ?>
            <form action='<?=$_SERVER["PHP_SELF"] ?>' id="forma">
                <input type="hidden" name="uusleht" value="jah" />
                <h2>Uue teate lisamine</h2>
                <dl>
                    <dt>Pealkiri:</dt>
                    <dd>
                        <input type="text" name="pealkiri"/>
                    </dd>
                    <dt>Teate sisu:</dt>
                    <dd>
                        <textarea rows="20" name="sisu"></textarea>
                    </dd>
                    <dt>Kuupäev:</dt>
                    <dd>
                        <input type="date" name="kuupaev"/>
                    </dd>
                </dl>
                <input type="submit" value="sisesta">
            </form>
            <?php
        }
        ?>
    </div>
</main>
</body>
</html>