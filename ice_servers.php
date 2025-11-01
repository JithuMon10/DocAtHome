<?php
/**
 * ice_servers.php
 *
 * This script acts as a secure proxy to the Xirsys API.
 *
 * Why is this necessary?
 * 1.  Security: Your Xirsys 'secret' key must be kept on the server. If it were in your
 *     JavaScript, anyone could view it and use your TURN server resources.
 * 2.  Temporary Credentials: TURN server credentials from services like Xirsys are
 *     short-lived. They must be fetched dynamically for each new call session.
 *
 * How it works:
 * 1.  The client-side JavaScript calls this PHP script.
 * 2.  This script makes a server-to-server cURL request to the Xirsys API,
 *     authenticating with your secret credentials.
 * 3.  Xirsys returns a temporary set of ICE servers (STUN/TURN).
 * 4.  This script forwards the ICE server configuration back to the client as JSON.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production if needed

// Your Xirsys Credentials
$ident = 'jithu2006';
$secret = '209c8266-9893-11f0-b87b-0242ac140002';
$channel = 'Docathome';

$xirsys_url = 'https://global.xirsys.net/_turn/' . $channel;

// Initialize cURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $xirsys_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['format' => 'ice']));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($ident . ':' . $secret)
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    // Provide a fallback to public STUN servers if Xirsys fails
    echo json_encode([
        'iceServers' => [
            ['urls' => 'stun:stun.l.google.com:19302'],
            ['urls' => 'stun:stun1.l.google.com:19302']
        ]
    ]);
    exit;
}

// Xirsys returns the data in a nested structure: { s: "ok", v: { iceServers: [...] } }
$data = json_decode($response, true);

// We only need to pass the 'v' part to the client
echo json_encode($data['v'] ?? ['iceServers' => []]);