<?php
$search = $_POST['search'];
echo "<h1 style=\"text-align:center;\">Search results for \"$search\"</h1>";

$servername = "localhost";
$username = "postgres";
$password = "postgres";
$dbname = "postgres";

// Create connection
$conn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
// Check connection
if (!$conn) {
  die("Connection failed: " . pg_connect_error());
}

$search=strtolower( $search);
$sql = "SELECT topic, url FROM od_topics WHERE lower(topic) LIKE '%$search%'";
$result = pg_query($conn, $sql);
while ($row = pg_fetch_assoc($result)) {
    $topic = $row['topic'];
    $url = $row['url'];
    echo "<html style='background-color:powderblue;'><ul style='text-align: left;position:relative; left:80px; top:2px; '>
        <li><form action='$url' method='post'><input type='hidden' name='topic' value='$topic'><input type='submit' value='$topic'></form></li>
    </ul>
    </html>";
}