<?php

include("../functions/functions.php");

$densite = (float)$_POST['densite'];

$sql = "SELECT Carat
        FROM bareme
        WHERE $densite BETWEEN Densite_min AND Densite_max
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo $row['Carat'];
} else {
    echo "";
}