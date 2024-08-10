<?php
header("Content-Type: application/json");

$response = json_encode([
    "ResultCode" => 0,
    "ResultDesc" => "Confirmation Received Successfully"
]);

$allowedIPs = [
    '196.201.214.200',
    '196.201.214.206',
    '196.201.213.114',
    '196.201.214.207',
    '196.201.214.208',
    '196.201.213.44',
    '196.201.212.127',
    '196.201.212.138',
    '196.201.212.129',
    '196.201.212.136',
    '196.201.212.74',
    '196.201.212.69',
];

// Get the client's IP address
$clientIP = $_SERVER['REMOTE_ADDR'];

// Check if the client's IP is in the allowed list
if (!in_array($clientIP, $allowedIPs)) {
    exit();
}

// Get the JSON response from M-Pesa
$mpesaResponse = file_get_contents('php://input');

// Optionally, you can save the raw JSON to a file for logging/debugging
$logFile = "mpesa.txt";
$log = fopen($logFile, "a");
fwrite($log, $mpesaResponse);
fclose($log);

// Decode the JSON response to an associative array
$mpesaData = json_decode($mpesaResponse, true);

// Database connection parameters
$servername = "localhost";
$username = "bizwizzy_tenderstoreltd";
$password = "EpaRCb@_JcYe";
$dbname = "bizwizzy_tenderstoreltd";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    // $stmt = $conn->prepare("INSERT INTO Mpesa_Payments (TransactionType, TransID, TransTime, TransAmount, BusinessShortCode, BillRefNumber, InvoiceNumber, OrgAccountBalance, ThirdPartyTransID, MSISDN, FirstName) 
    //                         VALUES (:TransactionType, :TransID, :TransTime, :TransAmount, :BusinessShortCode, :BillRefNumber, :InvoiceNumber, :OrgAccountBalance, :ThirdPartyTransID, :MSISDN, :FirstName)");
    
    $stmt = $conn->prepare("INSERT INTO mpesa_transactions (transaction_type, transaction_id, transaction_time, transaction_amount, business_short_code, bill_ref_number, invoice_number, org_account_balance, third_party_transaction_id, msisdn, first_name) 
                            VALUES (:TransactionType, :TransID, :TransTime, :TransAmount, :BusinessShortCode, :BillRefNumber, :InvoiceNumber, :OrgAccountBalance, :ThirdPartyTransID, :MSISDN, :FirstName)");

    // Bind the parameters
    $stmt->bindParam(':TransactionType', $mpesaData['TransactionType']);
    $stmt->bindParam(':TransID', $mpesaData['TransID']);
    $stmt->bindParam(':TransTime', $mpesaData['TransTime']);
    $stmt->bindParam(':TransAmount', $mpesaData['TransAmount']);
    $stmt->bindParam(':BusinessShortCode', $mpesaData['BusinessShortCode']);
    $stmt->bindParam(':BillRefNumber', $mpesaData['BillRefNumber']);
    $stmt->bindParam(':InvoiceNumber', $mpesaData['InvoiceNumber']);
    $stmt->bindParam(':OrgAccountBalance', $mpesaData['OrgAccountBalance']);
    $stmt->bindParam(':ThirdPartyTransID', $mpesaData['ThirdPartyTransID']);
    $stmt->bindParam(':MSISDN', $mpesaData['MSISDN']);
    $stmt->bindParam(':FirstName', $mpesaData['FirstName']);

    // Execute the statement
    $stmt->execute();

    // Send the confirmation response
    echo $response;
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
