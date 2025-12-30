<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost","root","","test");
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// INSERT
if(isset($_POST["submit"])){
    $vender = $_POST["vc"];
    $asn = $_POST["ASN"];
    $invoice = $_POST["IN"];
    $del = $_POST["DN"];
    $style = $_POST["style"];
    $loc = $_POST["loc"];

    $stmt = $conn->prepare("INSERT INTO details(vendercode,asn,invoice,delivery,stylecode,loc) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $vender, $asn, $invoice, $del, $style, $loc);

    if($stmt->execute()){
        echo "Data inserted successfully!";
    } else {
        echo "Insert error: " . $stmt->error;
    }
    $stmt->close();
}

// SEARCH
if(isset($_POST["fetch"])){
    $sty = $_POST["search"];
    $stmt = $conn->prepare("SELECT * FROM details WHERE stylecode = ?");
    $stmt->bind_param("s", $sty);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        echo "<table border='1' cellpadding='8'>
            <tr>
                <th>Vendor</th>
                <th>ASN</th>
                <th>Invoice</th>
                <th>Delivery</th>
                <th>Stylecode</th>
                <th>Location</th>
            </tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>{$row['vendercode']}</td>
                    <td>{$row['asn']}</td>
                    <td>{$row['invoice']}</td>
                    <td>{$row['delivery']}</td>
                    <td>{$row['stylecode']}</td>
                    <td>{$row['loc']}</td>
                 </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found for this style code.";
    }
    $stmt->close();
}

mysqli_close($conn);
?>
