<?php
/**
 * Test Piston Sandbox Execution
 */
$payload = array(
	'language' => 'python',
	'version'  => '3.10.0',
	'files'    => array(
		array(
			'name'    => 'main.py',
			'content' => "import sys\nprint('Hello from Code Elta6ur isolated sandbox!')\nprint(f'Python version: {sys.version.split()[0]}')",
		),
	),
);

$ch = curl_init('https://emkc.org/api/v2/piston/execute');
curl_setopt_array($ch, array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST           => true,
	CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
	CURLOPT_POSTFIELDS     => json_encode($payload),
	CURLOPT_TIMEOUT        => 10,
	CURLOPT_SSL_VERIFYPEER => false,
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
if ($err) {
	echo "cURL Error: " . $err . "\n";
} else {
	$data = json_decode($response, true);
	echo "Output from isolated sandbox:\n";
	if (isset($data['run'])) {
		echo "stdout: " . $data['run']['stdout'] . "\n";
		echo "exit code: " . $data['run']['code'] . "\n";
	} else {
		echo $response . "\n";
	}
}
