<?php
//Get and Decode JSON data
$data = json_decode(file_get_contents('php://input'), true);

//Vaidation Check
$error_msg = '';
//Checking if Name or Email is not null & rest of the keys are present
if (($data['first_name'] != null && $data['last_name'] != null && $data['email'] != null) && array_keys_exist($data, 'age', 'interests', 'admission_date', 'admission_time', 'is_active')) {
    
    //Database configurations
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "vectormedia";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Part 1 : Data Insertion
    //first, inserting the main person's record
    $sql_1 = "INSERT into person (id, first_name, last_name, age, email, admission_date, admission_time, is_active) VALUES 
						   (0,'{$data['first_name']}','{$data['last_name']}',{$data['age']},'{$data['email']}', 
						    '{$data['admission_date']}','{$data['admission_time']}','{$data['is_active']}')";
    $res   = $conn->query($sql_1);
    
    //now, inserting the person's interests in a separate table
    foreach ($data['interests'] as $interests) {
        $sql_2 = "INSERT into person_interests (person_email, interest) VALUES ('{$data['email']}', '$interests' )";
        
        $res = $conn->query($sql_2);
    }
    
    // Part 2: Data Retreival
    $sql_3 = "SELECT first_name, last_name, age, email, admission_date, admission_time, is_active from person where email = '{$data['email']}'";
    $result = $conn->query($sql_3) or die($conn->error);
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    //second part of the query
    $sql_4 = "SELECT interest from person_interests where person_email = '{$data['email']}'";
    $result = $conn->query($sql_4) or die($conn->error);
    $int = array();
    $i   = 1;
    while ($interest = $result->fetch_assoc()) {
        $int["interest" . $i] = $interest["interest"];
        $i++;
    }
    $rows[0]["interests"] = $int;
    
    print "<pre>";
    echo '<h1>Person Information:</h1><br>';
    print_r($rows);
} else {
    $error_msg = '<h3>There is a problem in the input file,Please note that all keys are required and values for Names & Email are required.<br> Please update in <a href="http://localhost/clientrequest/payload.json">THIS</a> file<br></h3>';
    echo $error_msg;
}

/*
Function to check if multiple keys exists in an array
*/
function array_keys_exist( array $array, $keys ) {
    $count = 0;
    if ( ! is_array( $keys ) ) {
        $keys = func_get_args();
        array_shift( $keys );
    }
    foreach ( $keys as $key ) {
        if ( array_key_exists( $key, $array ) ) {
            $count ++;
        }
    }
 
    return count( $keys ) === $count;
}
?>