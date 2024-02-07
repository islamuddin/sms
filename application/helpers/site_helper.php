<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function encryptSignature($data)
{
	$file = file_get_contents(base_url('uploads/rsa_private_key_vms.pem'));
	$openssl_private_key = openssl_get_privatekey($file);
	@openssl_private_encrypt($data, $signature, $openssl_private_key, OPENSSL_PKCS1_PADDING);
	@openssl_free_key($openssl_private_key);
	$sign = base64_encode($signature);
	if (empty($sign)) {
		return false;
	}
	return $sign;
}

function nadra($cnic)
{
	$CI = & get_instance();
	$signature = encryptSignature('4210184591795');
	$username = $CI->session->userdata('user_name');
	$userid = $CI->session->userdata('userId');
	if ($signature) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://127.0.0.1:8000/api/nvms',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'cnic=' . $cnic . '&username=' . $username . '&userid=' . $userid,
			CURLOPT_HTTPHEADER => array(
				'node_key: 9800((**&^^%OOPP',
				'code_key: OOPP))O**&&^%@$#^ASDSDHKASUKHWUE',
				'signature: ' . $signature,
				'Content-Type: application/x-www-form-urlencoded'
			),
		));
		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			return false;
		}
		curl_close($curl);
		return $response;
	} else {
		return false;
	}
}
