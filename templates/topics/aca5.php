<?php

if ($_REQUEST['username'] == null){
    include 'nav_bar.html';
}
else{
    global $username;
    $username = $_REQUEST['username'];
    include 'nav_bar_logged_in_php.html';
}


echo "<h1 style=\"text-align:center;\">ACA 5 Dialogue</h1>";


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

$sql = "SELECT id, username, created_at, text, likes, reply FROM od_tweet WHERE topic='ACA 5' ORDER BY id DESC";
$result = pg_query($conn, $sql);

#like and reply
if (isset($_POST['Like'])) {
    if ($_POST['Like']) {
        like();
    }
}


    function like() {
        #echo "The like function is called.";
        #increase # of likes by 1
        $likes2 = $_POST['likes'];
        $id2 = $_POST['id'];
        $sql_likes = "UPDATE od_tweet SET likes = $likes2+1 WHERE id=$id2";
        $conn2 = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
        $result2 = pg_query($conn2, $sql_likes);
        echo("<meta http-equiv='refresh' content='0.5'>");
        #exit;
    }

if (isset($_POST['Reply'])) {
    if ($_POST['Reply']) {
        reply();
    }
}

    function reply() {
        $post = $_REQUEST['text'];
        $todays = date('M d Y h:i');
        $topic = $_POST['topic'];
        $id = $_POST['id'];
        $reply = $_POST['reply'];
        $username2 = $_POST['username2'];
        $sql_reply = "INSERT INTO od_tweet (topic, username, created_at, text, likes, reply, topic_ref_id)
                VALUES ('$topic', '$username2', '$todays', '$post', 0, 'n', 1)";
        $conn3 = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
        $result_insert = pg_query($conn3, $sql_reply);

        $sql_max_id = "SELECT max(id) FROM od_tweet";
        $conn5 = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
        $result_max_id = pg_query($conn5, $sql_max_id);
        while($row = pg_fetch_array($result_max_id)) {
            $reply_id = $row['max'];
        }
        $final_reply = $reply." ".$reply_id;

        $sql_update = "UPDATE od_tweet SET reply = '$final_reply' WHERE id=$id";
        $conn6 = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
        $result_update = pg_query($conn6, $sql_update);
        echo("<meta http-equiv='refresh' content='0.5'>");
    }


$row=pg_num_rows($result);
if (0 < $row) {
    // output data of each row
    while($row = pg_fetch_assoc($result)) {
        $reply = $row['reply'];
        if (25 < strlen($row["created_at"]) ){
            #not a reply
            if ($reply != "n"){
                echo "<h3 style=\"background-color:powderblue;\"> Username: ".$row["username"]."</h3><h4 style=\"border: 1px solid grey; padding: 14px 16px;position:relative; left:30px;background-color:#faf6d2; border-radius: 15px;width: 90%;\">Date: " . substr($row["created_at"],4,6) .substr($row["created_at"],25,5). substr($row["created_at"],10,6) . "<br>".$row["text"] ."<br>". $row["likes"]." &hearts;</h4>\n";
                $id = $row['id'];
                $likes = $row['likes'];

                #likes and replies
                echo "<form style='display:inline-block;' method='post' action='http://127.0.0.1:3338/blm.php'>
                        <input type=\"hidden\" name=\"id\" value='$id' />
                        <input type=\"hidden\" name=\"likes\" value='$likes' />
                        <input type=\"submit\" name=\"Like\" value=\"Like\" />
                    </form>  <form style='display:inline-block;' method='post'><input type=\"submit\" name=\"$id\" value='Reply'/></form>";
                if (isset($_POST[$id])){
                    echo "<form action='http://127.0.0.1:3338/blm.php' method=\"post\" style='position:relative; left:30px;'>
                                      <input style=\"height:100px; width:1000px; \" type=\"text\" name=\"text\"><br>
                                      <input type=\"hidden\" name=\"topic\" value=\"ACA 5\">
                                      <input type=\"hidden\" name=\"id\" value='$id'/>
                                      <input type=\"hidden\" name=\"reply\" value='$reply'/>
                                      <input type=\"hidden\" name=\"username2\" value=\"Anon\">
                                      <input type=\"submit\" name=\"Reply\">
                                      </form>";
                }
            }

            #replies
            if ($reply != "" && $reply != "n") {
                $replies_array = explode(" ", $reply);
                foreach ($replies_array as &$value){
                    if ($value != ""){
                        $sql = "SELECT id, username, created_at, text, likes FROM od_tweet WHERE id=$value";
                        $conn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
                        $result_update = pg_query($conn, $sql);
                        while($row = pg_fetch_assoc($result_update)) {
                            echo "<h3 style=\"background-color:powderblue;position:relative; left:60px; width: 95%\">&rarrhk; Username: ".$row["username"]."</h3><h4 style=\"border: 1px solid grey; padding: 14px 16px;position:relative; left:90px;background-color:#faf6d2; border-radius: 15px;width: 90%;\">Date: " . substr($row["created_at"],4,6) .substr($row["created_at"],25,5). substr($row["created_at"],10,6) . "<br>".$row["text"] ."<br>". $row["likes"]." &hearts;</h4>\n";
                        }
                    }
                }
            }
        }
        else{
            #print replies
            $reply = $row['reply'];

            if ($reply != "n"){
                echo "<h3 style=\"background-color:powderblue;\"> Username: ".$row["username"]."</h3><h4 style=\"border: 1px solid grey; padding: 14px 16px; position:relative; left:30px; background-color:#faf6d2; border-radius: 15px;width: 90%;\">Date: " . $row["created_at"]."<br>".$row["text"]."</h4>\n";

                $id = $row['id'];
                $likes = $row['likes'];

                #likes
                echo "<form method='post' style='display:inline-block;' action='http://127.0.0.1:3341/blm.php'>
                        <input type=\"hidden\" name=\"id\" value='$id' />
                        <input type=\"hidden\" name=\"likes\" value='$likes' />
                        <input type=\"submit\" name=\"Like\" value=\"Like\" />
                    </form>  <form style='display:inline-block;' method='post'><input type=\"submit\" name=\"$id\" value='Reply'/></form>";
                if (isset($_POST[$id])){
                    echo "<form method=\"post\" style='position:relative; left:30px;'>
                                      <input style=\"height:100px; width:1000px; \" type=\"text\" name=\"text\"><br>
                                      <input type=\"hidden\" name=\"topic\" value=\"ACA 5\">
                                      <input type=\"hidden\" name=\"id\" value='$id'/>
                                      <input type=\"hidden\" name=\"username2\" value=\"Anon\">
                                      <input type=\"submit\" name=\"Reply\">
                                      </form>";
                }
            }
            if ($reply != "" && $reply != "n") {
                $replies_array = explode(" ", $reply);
                foreach ($replies_array as &$value){
                    if ($value != "" && $value != "n"){
                        $sql = "SELECT id, username, created_at, text, likes FROM od_tweet WHERE id=$value";
                        $conn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");
                        $result_update = pg_query($conn, $sql);
                        while($row = pg_fetch_assoc($result_update)) {
                            echo "<h3 style=\"background-color:powderblue;position:relative; left:60px; width:95%\">&rarrhk; Username: ".$row["username"]."</h3><h4 style=\"border: 1px solid grey; padding: 14px 16px;position:relative; left:90px;background-color:#faf6d2; border-radius: 15px;width: 90%;\">Date: " . $row["created_at"]. "<br>".$row["text"] ."<br>". $row["likes"]." &hearts;</h4>\n";
                        }
                    }
                }
            }
        }
    }
}
else {
    echo "0 results";
}

pg_close($conn);


