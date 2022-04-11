<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/');
        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCratePlateau()
    {
        $parameters = [
            'name' => 'Infinix',
            'region' => '{"x":32,"y":64}'
        ];

        $this->post("/plateau/create", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'name',
                    'region',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPlateauList()
    {
        $this->get("/plateau/list", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'name',
                    'region',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateRover()
    {
        $parameters = [
            'name' => 'Infinix',
            'location' => '{"x":32,"y":64}',
            'plateauId' => 1,
            'direction' => 'N',
        ];
        $this->post("/rover/create", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'name',
                    'location',
                    'plateauId',
                    'direction',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoverList()
    {
        $this->get("/rover/list", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'plateauId',
                    'name',
                    'direction',
                    'location',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRoverState()
    {
        $this->get("/rover/getState/1", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    "deneme Rover'ı deneme3 Platosunda  N yönünde {'x':0,'y':0} kordinantlarında yer almaktadır"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendCommandRover()
    {
        $parameters = [
            'commands' => 'RMMMMLMMMM',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                    "Rover Moved in Designated Directions"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldWithErrorCommand()
    {
        $parameters = [
            'commands' => 'LLK',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                    "K komut okunamadı"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldXCoordinateExit()
    {
        $parameters = [
            'commands' => 'LM',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                   "x coordinte cannot be less than 0"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldYCoordinateExit()
    {
        $parameters = [
            'commands' => 'LLM',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                    "y coordinte cannot be less than 0"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldXCoordinateOverExit()
    {
        $parameters = [
            'commands' => 'RMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                    "x coordinte You cannot enter a value greater than 32"
                ]
            ]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldYCoordinateOverExit()
    {
        $parameters = [
            'commands' => 'MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM',
        ];
        $this->put("/rover/setState/1", $parameters, []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' =>
                [
                    "y coordinte You cannot enter a value greater than 64."
                ]
            ]
        );
    }
}
