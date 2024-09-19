<?php

function responseSuccess($data = null, $code = 200, $message = 'Operation is successfully') {
	return [
		'success'		  =>	true,
		'statusCode'  =>	$code,
		'message'		  =>	$message,
		'data'			  =>	$data,
	];
}

function responseFailed($data = null, $code = 500, $message = 'Operation is failed') {
	return [
		'success'		  =>	false,
		'statusCode'	=>	$code,
		'message'		  =>	$message,
		'data'			  =>	$data,
	];
}