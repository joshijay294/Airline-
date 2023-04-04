<?php
// Oracle database connection parameters
$host = "localhost";
$port = "1521";
$database = "ORCL";
$username = "username";
$password = "password";

// Establish a connection to the database
$conn = oci_connect($username, $password, "//" . $host . ":" . $port . "/" . $database);
if (!$conn) {
    $error = oci_error();
    die("Connection failed: " . $error['message']);
}

// Retrieve flight data from the database
$sql = "SELECT * FROM flights WHERE source = :source AND destination = :destination AND date = :date";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":source", $_POST['source']);
oci_bind_by_name($stmt, ":destination", $_POST['destination']);
oci_bind_by_name($stmt, ":date", $_POST['date']);
oci_execute($stmt);

// Display the flight results in a table
if (oci_fetch_all($stmt, $results) > 0) {
    echo "<table>";
    echo "<tr><th>Flight Number</th><th>Airline</th><th>Source</th><th>Destination</th><th>Departure Time</th><th>Arrival Time</th><th>Price</th></tr>";
    foreach ($results as $row) {
        echo "<tr><td>" . $row['FLIGHT_NUMBER'] . "</td><td>" . $row['AIRLINE'] . "</td><td>" . $row['SOURCE'] . "</td><td>" . $row['DESTINATION'] . "</td><td>" . $row['DEPARTURE_TIME'] . "</td><td>" . $row['ARRIVAL_TIME'] . "</td><td>" . $row['PRICE'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No flights found.";
}

// Close the database connection
oci_free_statement($stmt);
oci_close($conn);
?>
