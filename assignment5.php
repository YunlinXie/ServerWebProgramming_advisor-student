<?php
/* *
 * author: Yunlin Xie
 * assignment 5 for CS174
 * preparation: create table named a5 in DB publications
 * to make things simple, we use varchar for all types
 * CREATE TABLE a5(advisorName VARCHAR(128),
 *    studentName VARCHAR(128),
 *    studentID VARCHAR(128),
 *    classCode VARCHAR(128));
 * */
###################################################################################
echo <<<_END
<html>
    <head>
        <title>Assignment #5</title>
    </head>
    <body>
        <form action="?" method = "POST">
            <pre>
                Advisor Name: <input type="text" name="advisor">
                Student Name: <input type="text" name="student">
                Student ID: <input type="text" name="id">
                Class Code: <input type="text" name="code">
                <input type="submit" value="ADD RECORD" name="insert">
            </pre>
            <pre>
                Search by advisor's name:
                <input type="text" name="searchA">
                <input type="submit" value="SEARCH" name="search">
            </pre>
        </form>
_END;

if(array_key_exists('insert',$_POST)) {
    insert();
}
if(array_key_exists('search',$_POST)) {
    search();
}
echo "</body></html>";
##################################################################################
function insert() {
    $hn = "localhost";
    $un = "root";
    $pw = "";
    $db = "publications";
    $conn = new mysqli($hn,$un,$pw,$db);
    if($conn->connect_error) {
        die("connection is failed!!!");
    }

    if(isset($_POST['insert'])) {
        if(isset($_POST['advisor']) && isset($_POST['student']) && isset($_POST['id']) && isset($_POST['code'])) {
            // spaces are considered as empty string
            $advisorName = trim(get_post($conn, 'advisor'));
            $studentName = trim(get_post($conn, 'student'));
            $studentID = trim(get_post($conn, 'id'));
            $classCode = trim(get_post($conn, 'code'));

            // do not do insertion for any empty inputs
            if(($advisorName==='') || ($studentName==='') || ($studentID==='') || ($classCode==='')) {
                echo "All areas should not be null!<br>";
                exit(0);
            }
            $query = "INSERT INTO a5 VALUES"."('$advisorName', '$studentName', '$studentID', '$classCode')";
            $result = $conn->query($query);

            // exit when insertion failed
            if(!$result) {
                die("<br>Insertion failed!<br>");
            }
            echo "Insert successfully!<br>";
            echo "Advisor: " .$advisorName."<br>";
            echo "Student: ".$studentName."<br>";
            echo "Student ID: ".$studentID."<br>";
            echo "Class Code: ".$classCode."<br>";
        }
    }
    $conn->close();
}

##################################################################################
function search() {
    $hn = "localhost";
    $un = "root";
    $pw = "";
    $db = "publications";
    $conn = new mysqli($hn,$un,$pw,$db);
    if($conn->connect_error) {
        die("connection is failed!!!");
    }

    if(isset($_POST['search'])) {
        // spaces are considered as empty string
        $searchAdvisor = trim(get_post($conn, 'searchA'));
        // do not handle any empty inputs
        if($searchAdvisor==='') {
            echo "Input should not be null!<br>";
            exit(0);
        }
        $query = "SELECT * FROM a5 WHERE advisorName="."'$searchAdvisor'";
        $result = $conn->query($query);

        // exit when searching failed
        if(!$result) {
            die("<br>Searching failed!<br>");
        }   
        
        $rows = $result->num_rows;
        echo "<table><tr><th>Advisor</th><th>Student</th><th>StudentID</th><th>ClassCode</th></tr>";
        for($i=0; $i<$rows; $i++) {
            $result->data_seek($i);
            $row = $result->fetch_array(MYSQLI_NUM);
            echo "<tr>";
            for($j=0; $j<4; $j++) {
                echo "<td>$row[$j]</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        $result->close();
    }
    $conn->close();
}

##################################################################################
function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
}

?>


