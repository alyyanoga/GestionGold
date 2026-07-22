<?php

include("functions.php");

$sql = "SELECT * FROM temptransactionor ORDER BY Id ASC";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)){
?>

<tr data-id="<?= $row['Id']; ?>">
    <td><?= number_format($row['Base'],0,',',' '); ?></td>
    <td><?= number_format($row['PoidsAir'],2,',',' '); ?></td>
    <td><?= number_format($row['PoidsEau'],2,',',' '); ?></td>
    <td><?= number_format($row['Densite'],2,',',' '); ?></td>
    <td><?= number_format($row['Carat'],2,',',' '); ?></td>
    <td><?= number_format($row['Montant'],0,',',' '); ?></td>
</tr>

<?php
}
?>