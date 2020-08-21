<?php
if ($_REQUEST['username'] == null){
    include '../nav_bar.html';
}
else{
    global $username;
    $username = $_REQUEST['username'];
    include 'nav_bar_logged_in_php.html';
}


$topic = $_POST['topic'];
$username = $_POST['username'];

echo "<body style='background-color:#b7e8e3'>";
echo "<h1 style=\"text-align:center;\">Create Post On <u>$topic</u></h1>";

echo "<h2>Share Something: </h2>" . $_POST["tweet"];

echo "<html> <form method='post'>
    <input style=\"height:100px; width:1000px; \" type=\"text\" name=\"text\"><br>
    <input type=\"hidden\" name=\"topic\" value=\"$topic\">
    <input type=\"hidden\" name=\"username\" value=\"$username\">
    <input type=\"submit\" name=\"submit\">
    </form>
    </html>";


$servername = "localhost";
$username = "postgres";
$password = "postgres";
$dbname = "postgres";

// Create connection
$conn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

try {
    $_POST['new_topic'];
    $sql2 = "INSERT INTO od_topics (topic, url) VALUES ('$topic', 'http://127.0.0.1:3345/new_topic.php')";
    @pg_query($conn, $sql2); //@ ignores the exception
}
finally {
 // Check if the form is submitted
if (isset($_POST['submit'])) {
    $post = $_REQUEST['text'];
    $todays = date('M d Y h:i');
    $topic = $_POST['topic'];
    $username = $_POST['username'];

    $sql_max_id = "SELECT max(id) FROM od_topics";
    $result_max_id = pg_query($conn, $sql_max_id);
    while($row = pg_fetch_array($result_max_id)) {
        $id = $row['max'];
    }
    $sql = "INSERT INTO od_tweet (topic_ref_id, topic, username, likes, reply, created_at, text)
        VALUES ($id, '$topic', '$username', 0, 0, '$todays', '$post')";
    #get url for correct topic
    $sql_url = "SELECT url FROM od_topics WHERE topic='$topic'";
    $result_url = pg_query($conn, $sql_url);
    while($row = pg_fetch_array($result_url)) {
            $url = $row['url'];
    }
    if (pg_query($conn, $sql)) {
        echo "Post created successfully. Click <form style='display: inline-block;' action='$url' method='post'><input type='hidden' name='topic' value='$topic'><input type='hidden' name='username' value='$username'><input style='background: none; border: none; padding: 0; color: #069; text-decoration: underline; cursor: pointer;' type='submit' value='here'></form> to view discussion.";
    } else {
        echo "Error: " . $sql . "<br>" . pg_error($conn);
    }
}
pg_close($conn);
}
