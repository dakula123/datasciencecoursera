
 <?php
$a = "mysql:dbname=eventseventos";
$u = "";
$p = "";
try {
$conn = new PDO( $a, $u, $p );
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {
echo "Connection failed: " . $e->getMessage();
}
$sql = "SELECT * FROM orga";
echo "<ul>";
try {
$rows = $conn->query( $sql );
foreach ( $rows as $row ) {
echo "<li>A " . $row["username"] . " is " . $row["password"] . "</li>";
}
} catch ( PDOException $e ) {
echo "Query failed: " . $e->getMessage();
}