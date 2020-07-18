<?php
namespace Tracker\tests;

class userTest extends ApiTestCase
{
    public function testLoginOk()
    {
        $this->request('POST', '/v1/login', ['email' => 'vipontes70@gmail.com', 'password' => 'admin']);

        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json');
        //$this->assertArrayHasKey('id', $this->responseData());
    }

    public function testLoginError()
    {
        $this->request('POST', '/v1/login', ['email' => 'vipontes70@gmail.com', 'password' => 'admin222']);

        $this->assertThatResponseHasStatus(401);
        $this->assertThatResponseHasContentType('application/json');
        //$this->assertArrayHasKey('id', $this->responseData());
    }

}
