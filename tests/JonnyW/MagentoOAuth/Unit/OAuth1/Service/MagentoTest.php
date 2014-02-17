<?php

/*
 * This file is part of the Magento OAuth package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\MagentoOAuth\Unit\OAuth1\Service;

use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Signature\SignatureInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Http\Uri\Uri;
use JonnyW\MagentoOAuth\OAuth1\Service\Magento;

/**
 * Magento OAuth
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class MagentoTest extends \PHPUnit_Framework_TestCase
{	
	/**
	 * Test that OAuth common exception is thrown
	 * when no Base URI instance is set in constructor
	 *
	 * @return void
	 */
	public function testOAuthCommonExceptionIsThrownWhenNoBaseUriInstanceIsSetInConstructor()
	{
		$this->setExpectedException('OAuth\Common\Exception\Exception');
		
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, null);
	}

	/**
	 * Test get request token endpoint returns
	 * instance of URI
	 *
	 * @return void
	 */
	public function testGetRequestTokenEndpointReturnsInstanceOfUri()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$this->assertInstanceOf('OAuth\Common\Http\Uri\Uri', $magento->getRequestTokenEndpoint());
	}
	
	/**
	 * Test get request token endpoint set initiate
	 * path on URI instance
	 *
	 * @return void
	 */
	public function testGetRequestTokenEndpointSetsInitiatePathOnUriInstance()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$uri->expects($this->once())
			->method('setPath')
			->with($this->identicalTo('oauth/initiate'));
		
		$magento->getRequestTokenEndpoint();
	}
	
	/**
	 * Test get authorization endpoint returns
	 * instance of URI
	 *
	 * @return void
	 */
	public function testGetAuthorizationEndpointReturnsInstanceOfUri()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$this->assertInstanceOf('OAuth\Common\Http\Uri\Uri', $magento->getAuthorizationEndpoint());
	}
	
	/**
	 * Test get authorization endpoint set authorize
	 * path on URI instance
	 *
	 * @return void
	 */
	public function testGetAuthorizationEndpointSetsAuthorizePathOnUriInstance()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$uri->expects($this->once())
			->method('setPath')
			->with($this->identicalTo('oauth/authorize'));
		
		$magento->getAuthorizationEndpoint();
	}
	
	/**
	 * Test get access token endpoint returns
	 * instance of URI
	 *
	 * @return void
	 */	
	public function testGetAccessTokenEndpointReturnsInstanceOfUri()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$this->assertInstanceOf('OAuth\Common\Http\Uri\Uri', $magento->getAccessTokenEndpoint());
	}

	/**
	 * Test get access token endpoint set token
	 * path on URI instance
	 *
	 * @return void
	 */
	public function testGetAccessTokenEndpointSetsTokenPathOnUriInstance()
	{
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$uri->expects($this->once())
			->method('setPath')
			->with($this->identicalTo('oauth/token'));
		
		$magento->getAccessTokenEndpoint();
	}
	
	/**
	 * Test parse request token response throws a
	 * token response exception if response body
	 * is null
	 *
	 * @return void
	 */
	public function testParseRequestTokenResponseThrowsTokenResponseExceptionIfResponseBodyIsNull()
	{
		$this->setExpectedException('OAuth\Common\Http\Exception\TokenResponseException');
		
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$parseRequestTokenResponse = new \ReflectionMethod(get_class($magento), 'parseRequestTokenResponse');
		$parseRequestTokenResponse->setAccessible(true);
		$parseRequestTokenResponse->invoke($magento, null);
	}
	
	/** 
	 * Test parse request token response throws
	 * a token response exception if response 
	 * body is not an array
	 *
	 * @return void
	 */
	public function testParseRequestTokenResponseThrowsTokenResponseExceptionIfResponseBodyIsNotAnArray()
	{
		$this->setExpectedException('OAuth\Common\Http\Exception\TokenResponseException');
		
		$credentials 	= $this->getCredentials();
		$httpClient 	= $this->getHttpClient();
		$tokenStorage 	= $this->getTokenStorage();
		$signature 		= $this->getSignature();
		$uri 			= $this->getUri();
		
		$magento = $this->getMagentoService($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		$parseRequestTokenResponse = new \ReflectionMethod(get_class($magento), 'parseRequestTokenResponse');
		$parseRequestTokenResponse->setAccessible(true);
		$parseRequestTokenResponse->invoke($magento, 'Test body that is not an array');
	}

	/**
	 * Get Magento service instance
	 *
	 * @param OAuth\Common\Consumer\CredentialsInterface $credentials
	 * @param OAuth\Common\Http\Client\ClientInterface $httpClient
	 * @param OAuth\Common\Storage\TokenStorageInterface $tokenStorage
	 * @param OAuth\OAuth1\Signature\SignatureInterface $signature
	 * @param OAuth\Common\Http\Uri\Uri $uri
	 * @return JonnyW\MagentoOAuth\Unit\OAuth1\Service\Magento
	 */
	protected function getMagentoService(CredentialsInterface $credentials, ClientInterface $httpClient, TokenStorageInterface $tokenStorage, SignatureInterface $signature, Uri $uri = null)
	{
		$magentoService = new Magento($credentials, $httpClient, $tokenStorage, $signature, $uri);
		
		return $magentoService;
	}
	
	/** 
	 * Get mock credentials instance
	 *
	 * @return OAuth\Common\Consumer\CredentialsInterface
	 */
	protected function getCredentials()
	{
		$mockCredentials = $this->getMock('OAuth\Common\Consumer\CredentialsInterface');
		
		return $mockCredentials;
	}
	
	/**
	 * Get mock HTTP client instnace
	 * 
	 * @return OAuth\Common\Http\Client\ClientInterface
	 */
	protected function getHttpClient()
	{
		$mockHttpClient = $this->getMock('OAuth\Common\Http\Client\ClientInterface');
		
		return $mockHttpClient;
	}
	
	/** 
	 * Get mock token storage
	 *
	 * @return OAuth\Common\Storage\TokenStorageInterface
	 */

	protected function getTokenStorage()
	{
		$mockTokenStorage = $this->getMock('OAuth\Common\Storage\TokenStorageInterface');
		
		return $mockTokenStorage;
	}
	
	/**
	 * Get mock signature instance
	 *
	 * @return OAuth\OAuth1\Signature\SignatureInterface
	 */
	protected function getSignature()
	{
		$mockSignature = $this->getMock('OAuth\OAuth1\Signature\SignatureInterface');
		
		return $mockSignature;
	}
	
	/**
	 * Get URI instance
	 *
	 * @return OAuth\Common\Http\Uri\Uri
	 */
	protected function getUri()
	{
		$mockUri = $this->getMock('OAuth\Common\Http\Uri\Uri');
		
		return $mockUri;
	}
}