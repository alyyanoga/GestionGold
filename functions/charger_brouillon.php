<?php

include("../functions/functions.php");

$sql = "SELECT * FROM brouillon_achat_or ORDER BY Id DESC";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {

echo "
<tr class='select_client'
onclick=\"selectionnerBrouillon(
'{$row['Id']}',
'{$row['Dates']}',
'{$row['CompteClient']}'
'{$row['ClientFournisseur']}',
'{$row['RepClientFournisseur']}',
'{$row['Quantites']}',
'{$row['Bases']}',
'{$row['Montants']}',
'{$row['Mouvements']}',
'{$row['Rgmt']}',
'{$row['Agents']}',
'{$row['idClient']}'
)\">
<td>{$row['Id']}</td>
<td>{$row['Dates']}</td>
<td>{$row['CompteClient']}</td>
<td>{$row['ClientFournisseur']}</td>
<td>{$row['RepClientFournisseur']}</td>
<td>{$row['Quantites']}</td>
<td>" . number_format($row['Bases'], 0, ',', ' ') . "</td>
<td>" . number_format($row['Montants'], 0, ',', ' ') . "</td>
<td>{$row['Mouvements']}</td>
<td>{$row['Rgmt']}</td>
<td>{$row['Agents']}</td>

</tr>";
}