<?php
namespace InterNations\Component\Testing;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Request;

trait GuzzleAssertionTrait
{
    /**
     * @var MockPlugin
     */
    protected $httpMockPlugin;

    /**
     * @param string $method Expected HTTP method
     * @param string $url Expected HTTP URL
     * @param integer $position
     */
    protected function assertHttpRequestWas($method, $url, $position = 0)
    {
        $requests = $this->httpMockPlugin->getReceivedRequests();

        /** @var $request Request */
        $request = $requests[$position];

        $this->assertSame($method, $request->getMethod());
        $this->assertSame($url, $request->getUrl());
    }

    /**
     * Create Guzzle response from a file
     *
     * @param string $file
     * @param integer $responseCode
     * @param array $headers
     * @return Response
     */
    protected function createGuzzleResponseFromFile($file, $responseCode = 200, $headers = [])
    {
        return new Response($responseCode, $headers, file_get_contents($file));
    }
}
