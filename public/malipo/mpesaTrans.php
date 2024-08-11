<?php
$servername = "localhost";
$username = "bizwizzy_tenderstoreltd";
$password = "EpaRCb@_JcYe";
$dbname = "bizwizzy_tenderstoreltd";

// JSON data
$json_data = '{
    "TransactionType": "Customer Merchant Payment",
    "TransID": "SGM3J225DR",
    "TransTime": "20240722205801",
    "TransAmount": "11720.00",
    "BusinessShortCode": "166373",
    "BillRefNumber": "",
    "InvoiceNumber": "",
    "OrgAccountBalance": "26781.80",
    "ThirdPartyTransID": "",
    "MSISDN": "ec59ead34e9e674f659b8602d4742a5053 ***** 134b8da0889ccb542bb64bb95",
    "FirstName": "JOSEPH"
}';

// Parse JSON data
$data = json_decode($json_data, true);

// Convert transaction time to MySQL DATETIME format
$trans_time = $data['TransTime'];
$trans_time_formatted = substr($trans_time, 0, 4) . '-' . substr($trans_time, 4, 2) . '-' . substr($trans_time, 6, 2) . ' ' . substr($trans_time, 8, 2) . ':' . substr($trans_time, 10, 2) . ':' . substr($trans_time, 12, 2);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$sql = "INSERT INTO mpesa_transactions (
    transaction_type, transaction_id, transaction_time, transaction_amount, business_short_code, bill_ref_number, invoice_number, org_account_balance, third_party_transaction_id, msisdn, first_name
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssdsdsssss", 
    $transaction_type, 
    $transaction_id, 
    $transaction_time, 
    $transaction_amount, 
    $business_short_code, 
    $bill_ref_number, 
    $invoice_number, 
    $org_account_balance, 
    $third_party_transaction_id, 
    $msisdn, 
    $first_name
);

// Set parameters and execute
$transaction_type = $data['TransactionType'];
$transaction_id = $data['TransID'];
$transaction_time = $trans_time_formatted;
$transaction_amount = floatval($data['TransAmount']);
$business_short_code = $data['BusinessShortCode'];
$bill_ref_number = $data['BillRefNumber'];
$invoice_number = $data['InvoiceNumber'];
$org_account_balance = floatval($data['OrgAccountBalance']);
$third_party_transaction_id = $data['ThirdPartyTransID'];
$msisdn = $data['MSISDN'];
$first_name = $data['FirstName'];

if ($stmt->execute() === false) {
    die("Execute failed: " . $stmt->error);
}

echo "New record created successfully";

$stmt->close();
$conn->close();
?>
