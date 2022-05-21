<?php
namespace braga\tools\security;
class AuthTokenResponse
{
	public $access_token;
	public $expires_in;
	public $refresh_expires_in;
	public $refresh_token;
	public $token_type;
	// public $not-before-policy;
	public $session_state;
	public $scope;
}

