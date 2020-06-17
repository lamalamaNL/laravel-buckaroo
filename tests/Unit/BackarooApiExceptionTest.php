<?php namespace LamaLama\LaravelBuckaroo\Tests\Unit;


use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use LamaLama\LaravelBuckaroo\Exceptions\BuckarooApiException;
use LamaLama\LaravelBuckaroo\Tests\TestCase;

class BackarooApiExceptionTest extends TestCase
{

    /** @test */
    public function it_creates_a_response_with_same_status_code()
    {
        $statusCode = 500;
        $exception = $this->createException($statusCode);
        $buckarooException = BuckarooApiException::createFromException($exception);
        $this->assertEquals($statusCode, $buckarooException->toResponse()->getStatusCode());

        $statusCode = 401;
        $exception = $this->createException($statusCode);
        $buckarooException = BuckarooApiException::createFromException($exception);
        $this->assertEquals($statusCode, $buckarooException->toResponse()->getStatusCode());

    }

    /** @test */
    public function it_will_not_show_error_details_from_buckaroo_api_when_in_debug_mode()
    {
        Config::set('app.debug', false);
        $exception = $this->createException();
        $buckarooException = BuckarooApiException::createFromException($exception);
        $body = json_decode($buckarooException->toResponse()->getContent(), true);
        $this->assertArrayNotHasKey('response_from_buckaroo', $body);

    }

    /** @test */
    public function it_will_show_error_details_from_buckaroo_api_when_in_debug_mode()
    {
        Config::set('app.debug', true, );
        $buckApiMessage = ['result' => 'test'];
        $exception = $this->createException(500, "test", $buckApiMessage);
        $buckarooException = BuckarooApiException::createFromException($exception);
        $body = json_decode($buckarooException->toResponse()->getContent(), true);
        $this->assertArrayHasKey('response_from_buckaroo', $body);
        $this->assertEquals(json_encode($buckApiMessage, true), $body['response_from_buckaroo']);

    }


    private function createException($statusCode = 500, $message = 'Something went wrong', $body = [
            'status' => 'failed',
            'reason' => 'testing'
        ]) : BadResponseException
    {
        $request = new Request('POST', 'http://test.com/test');
        $response = new Response($statusCode, [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ], json_encode($body));
        $testResponse = new BadResponseException($message, $request, $response);
        return $testResponse;
    }

}