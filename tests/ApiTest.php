<?php

class ApiTest extends TestCase
{
    /**
     * Test the User API route
     */
    public function testUserApi()
    {
        $this->call('GET', 'api/user/18');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");
    }

    /**
     * Test the User Friends API route
     */
    public function testUserFriendsApi()
    {
        $this->call('GET', 'api/user/1/friends');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");
    }

    /**
     * Test the User Indirect Friends API route
     */
    public function testUserFriendsIndirectApi()
    {
        $this->call('GET', 'api/user/1/friends/indirect');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");
    }

    /**
     * Test the User Indirect Friends API route
     */
    public function testUserFriendsSuggestApi()
    {
        $this->call('GET', 'api/user/7/friends/suggest');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");
    }
}