<?php

use SocialGraph\User\Models\User;

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
     * Test the user Marie
     */
    public function testUserMarieApi()
    {
        $id = 18;
        $user = User::find($id);

        $this->call('GET', 'api/user/'.$id);
        $this->assertResponseOk();

        // Make sure the values fit
        $data = json_decode($this->response->getContent());
        $this->assertTrue($data->id == $user->id);
        $this->assertTrue($data->firstname == $user->firstname);
        $this->assertTrue($data->surname == $user->surname);
        $this->assertTrue($data->age == $user->age);
        $this->assertTrue($data->gender == $user->gender);
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
     * Test the User Rob Friends API
     */
    public function testUserRobFriendsApi()
    {
        $user = User::find(2);

        $this->call('GET', 'api/user/'.$user->id.'/friends');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");

        // Get the friends from Rob
        $friends = $user->friends->lists('id');

        // Compare API friends to Eloquent Collection friends
        $data = json_decode($this->response->getContent());
        foreach($data as $friend) {
            $this->assertTrue(in_array($friend->id, $friends));
        }
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
     * Test that Sarah has 10 indirect friends (her friends have many friends)
     */
    public function testUserSarahFriendsIndirectApi()
    {
        $user = User::find(7);

        $this->call('GET', 'api/user/'.$user->id.'/friends/indirect');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");

        // Now parse the data
        $friends = json_decode($this->response->getContent());

        $this->assertTrue(count($friends) == 10);
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

    /**
     * Test the User Suggest Api returns Sandra and Lisa for Sarah
     */
    public function testUserSarahSuggestApi()
    {
        $this->call('GET', 'api/user/7/friends/suggest');
        $this->assertResponseOk();

        // Make sure it's JSON
        $this->assertJson($this->response->getContent(), "Expected JSON format.");

        // Does it have Sandra and Lisa?
        $friends = 0;
        $data = json_decode($data = $this->response->getContent());
        foreach($data as $suggestion) {
            if(in_array($suggestion->id, array(11, 13))) {
                $friends++;
            }
        }

        // We have found two friends
        $this->assertTrue($friends == 2);
    }
}