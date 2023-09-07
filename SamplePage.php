<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

VerifyDataTable($connection, DB_DATABASE);

$data_name = htmlentities($_POST['DATA_NAME']);
$data_email = htmlentities($_POST['DATA_EMAIL']);
$data_phone = htmlentities($_POST['DATA_PHONE']);
$data_address = htmlentities($_POST['DATA_ADDRESS']);

if (strlen($data_name) || strlen($data_email) || strlen($data_phone) || strlen($data_address)) {
    AddData($connection, $data_name, $data_email, $data_phone, $data_address);
}
?>

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <h2>Add Data</h2>
    <table border="0">
        <tr>
            <td>NAME</td>
            <td>EMAIL</td>
            <td>PHONE</td>
            <td>ADDRESS</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="DATA_NAME" maxlength="45" size="30" />
            </td>
            <td>
                <input type="text" name="DATA_EMAIL" maxlength="100" size="60" />
            </td>
            <td>
                <input type="text" name="DATA_PHONE" maxlength="20" size="20" />
            </td>
            <td>
                <input type="text" name="DATA_ADDRESS" maxlength="90" size="60" />
            </td>
            <td>
                <input type="submit" value="Add Data" />
            </td>
        </tr>
    </table>
</form>

<!-- Display Data table data. -->
<h2>Data</h2>
<table border="1" cellpadding="2" cellspacing="2">
    <tr>
        <td>ID</td>
        <td>NAME</td>
        <td>EMAIL</td>
        <td>PHONE</td>
        <td>ADDRESS</td>
    </tr>

    <?php
    $result = mysqli_query($connection, "SELECT * FROM DATA");

    while ($query_data = mysqli_fetch_row($result)) {
        echo "<tr>";
        echo "<td>", $query_data[0], "</td>",
        "<td>", $query_data[1], "</td>",
        "<td>", $query_data[2], "</td>",
        "<td>", $query_data[3], "</td>",
        "<td>", $query_data[4], "</td>";
        echo "</tr>";
    }
    ?>

</table>

<?php

mysqli_free_result($result);
mysqli_close($connection);

?>

</body>
</html>

<?php

/* adiciona dados na tabela */
function AddData($connection, $name, $email, $phone, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $e = mysqli_real_escape_string($connection, $email);
    $p = mysqli_real_escape_string($connection, $phone);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "INSERT INTO DATA (NAME, EMAIL, PHONE, ADDRESS) VALUES ('$n', '$e', '$p', '$a');";

    if (!mysqli_query($connection, $query)) echo("<p>Error adding data.</p>");
}

function VerifyDataTable($connection, $dbName) {
    if (!TableExists("DATA", $connection, $dbName)) {
        $query = "CREATE TABLE DATA (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            EMAIL VARCHAR(100),
            PHONE int(20),
            ADDRESS TEXT
          )"; 

        if (!mysqli_query($connection, $query)) echo("<p>Error creating DATA table.</p>");
    }
}

/* Check whether a table exists. */
function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    if (mysqli_num_rows($checktable) > 0) return true;

    return false;
}
?>
