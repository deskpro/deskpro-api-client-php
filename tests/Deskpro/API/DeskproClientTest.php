<?php

/*
 * DeskPRO (r) has been developed by Deskpro Ltd. https://www.deskpro.com/
 * a British company located in London, England.
 *
 * All source code and content Copyright (c) 2025, Deskpro Ltd.
 *
 * The license agreement under which this software is released
 * can be found at https://www.deskpro.com/eula/
 *
 * By using this software, you acknowledge having read the license
 * and agree to be bound thereby.
 *
 * Please note that DeskPRO is not free software. We release the full
 * source code for our software because we trust our users to pay us for
 * the huge investment in time and energy that has gone into both creating
 * this software and supporting our customers. By providing the source code
 * we preserve our customers' ability to modify, audit and learn from our
 * work. We have been developing DeskPRO since 2001, please help us make it
 * another decade.
 *
 * Like the work you see? Think you could make it better? We are always
 * looking for great developers to join us: https://www.deskprocom/jobs/
 *
 * ~ Thanks, Everyone at Team Deskpro
 */

use PHPUnit\Framework\TestCase;
use Deskpro\API\DeskproClient;
use Deskpro\API\APIResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * @coversDefaultClass \DeskPRO\API\DeskPROClient
 */
class DeskproClientTest extends TestCase
{
    /**
     * @covers ::setAuthToken
     */
    public function testSetAuthToken()
    {
        $client = $this->getMockClient();
        $this->assertSame($client, $client->setAuthToken(1, 'testing'));
    }

    /**
     * @covers ::setAuthKey
     */
    public function testSetAuthKey()
    {
        $client = $this->getMockClient();
        $this->assertSame($client, $client->setAuthKey(1, 'testing'));
    }

    /**
     * @covers ::setHelpdeskUrl
     * @covers ::getHelpdeskUrl
     */
    public function testSetGetHelpdeskUrl()
    {
        $client = $this->getMockClient();
        $this->assertSame($client, $client->setHelpdeskUrl('testing'));
        $this->assertEquals('testing', $client->getHelpdeskUrl());
    }

    /**
     * @covers ::getHTTPClient
     * @covers ::setHTTPClient
     */
    public function testSetGetHTTPClient()
    {
        $client = $this->getMockClient();
        $httpClient = new Client();

        $this->assertSame($client, $client->setHTTPClient($httpClient));
        $this->assertSame($httpClient, $client->getHTTPClient());
    }

    /**
     * @covers ::getDefaultHeaders
     * @covers ::setDefaultHeaders
     */
    public function testSetGetDefaultHeaders()
    {
        $client = $this->getMockClient();
        $headers = [
            'X-Custom-Value' => 'testing'
        ];

        $this->assertSame($client, $client->setDefaultHeaders($headers));
        $this->assertSame($headers, $client->getDefaultHeaders());
    }

    /**
     * @covers ::request
     */
    public function testRequest()
    {
        $client = $this->getMockClient([
            new Response(200, [], json_encode([]))
        ]);
        $resp = $client->request('HEAD', '/articles');

        $this->assertIsArray($resp);
    }

    /**
     * @covers ::requestAsync
     */
    public function testRequestAsync()
    {
        $client = $this->getMockClient([
            new Response(200, [], json_encode([]))
        ]);
        $promise = $client->requestAsync('HEAD', '/articles');
        $resp = $promise->wait();

        $this->assertIsArray($resp);
    }

    /**
     * @covers ::get
     */
    public function testGet()
    {
        $body = [
            'data' => [
                [
                    'id' => 101,
                    'person' => 352,
                    'language' => 1,
                    'slug' => 'exercitationem-illo-quod-et-provident',
                    'title' => 'Exercitationem illo quod et provident',
                    'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.',
                    'view_count' => 33,
                    'total_rating' => 15,
                    'num_comments' => 1,
                    'num_ratings' => 14,
                    'status' => 'published',
                    'date_created' => '2025-11-16T13:40:04+0000',
                    'date_updated' => '2025-11-27T12:43:34+0000',
                    'date_published' => '2025-11-27T12:43:34+0000'
                ]
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($body))
        ]);
        $resp = $client->get('/articles');
        $data = $resp->getData();
        $meta = $resp->getMeta();

        $this->assertEquals($data[0], $body['data'][0]);
        $this->assertEquals($meta['count'], $body['meta']['count']);
    }

    /**
     * @covers ::getAsync
     */
    public function testGetAsync()
    {
        $body = [
            'data' => [
                [
                    'id' => 101,
                    'person' => 352,
                    'language' => 1,
                    'slug' => 'exercitationem-illo-quod-et-provident',
                    'title' => 'Exercitationem illo quod et provident',
                    'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.',
                    'view_count' => 33,
                    'total_rating' => 15,
                    'num_comments' => 1,
                    'num_ratings' => 14,
                    'status' => 'published',
                    'date_created' => '2025-11-16T13:40:04+0000',
                    'date_updated' => '2025-11-27T12:43:34+0000',
                    'date_published' => '2025-11-27T12:43:34+0000'
                ]
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($body))
        ]);
        $promise = $client->getAsync('/articles');
        $resp = $promise->wait();
        $data = $resp->getData();
        $meta = $resp->getMeta();

        $this->assertEquals($data[0], $body['data'][0]);
        $this->assertEquals($meta['count'], $body['meta']['count']);
    }

    /**
     * @covers ::post
     */
    public function testPost()
    {
        $body = [
            'title'              => 'This is a title',
            'content'            => 'This is the content',
            'content_input_type' => 'rte',
            'status'             => 'published'
        ];

        $postResp = [
            'data' => [
                $body
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($postResp))
        ]);
        $resp = $client->post('/articles', $body);
        $data = $resp->getData();

        $this->assertEquals($data[0], $body);
    }

    /**
     * @covers ::postAsync
     */
    public function testPostAsync()
    {
        $body = [
            'title'              => 'This is a title',
            'content'            => 'This is the content',
            'content_input_type' => 'rte',
            'status'             => 'published'
        ];

        $postResp = [
            'data' => [
                $body
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($postResp))
        ]);
        $promise = $client->postAsync('/articles', $body);
        $resp = $promise->wait();
        $data = $resp->getData();

        $this->assertEquals($data[0], $body);
    }

    /**
     * @covers ::put
     */
    public function testPut()
    {
        $body = [
            'title'              => 'This is a title',
            'content'            => 'This is the content',
            'content_input_type' => 'rte',
            'status'             => 'published'
        ];

        $postResp = [
            'data' => [
                $body
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($postResp))
        ]);
        $resp = $client->put('/articles', $body);
        $data = $resp->getData();

        $this->assertEquals($data[0], $body);
    }

    /**
     * @covers ::putAsync
     */
    public function testPutAsync()
    {
        $body = [
            'title'              => 'This is a title',
            'content'            => 'This is the content',
            'content_input_type' => 'rte',
            'status'             => 'published'
        ];

        $postResp = [
            'data' => [
                $body
            ],
            'meta' => [
                'count' => 1
            ],
            'linked' => []
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($postResp))
        ]);
        $promise = $client->putAsync('/articles', $body);
        $resp = $promise->wait();
        $data = $resp->getData();

        $this->assertEquals($data[0], $body);
    }

    /**
     * @covers ::delete
     */
    public function testDelete()
    {
        $client = $this->getMockClient([
            new Response(200, [], json_encode([]))
        ]);
        $resp = $client->delete('/articles/1');

        $this->assertIsArray($resp);
    }

    /**
     * @covers ::deleteAsync
     */
    public function testDeleteAsync()
    {
        $client = $this->getMockClient([
            new Response(200, [], json_encode([]))
        ]);
        $promise = $client->deleteAsync('/articles/1');
        $resp = $promise->wait();

        $this->assertIsArray($resp);
    }

    /**
     * @covers ::batch
     */
    public function testBatch()
    {
        $body = [
            'responses' => [
                '101' => [
                    'data' => [
                        'id' => 101,
                        'title' => 'Exercitationem illo quod et provident',
                        'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.'
                    ],
                    'meta' => [
                        'count' => 1
                    ],
                    'linked' => []
                ],
                '102' => [
                    'data' => [
                        'id' => 102,
                        'title' => 'Exercitationem illo quod et provident',
                        'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.'
                    ],
                    'meta' => [
                        'count' => 1
                    ],
                    'linked' => []
                ]
            ]
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($body))
        ]);
        $resp = $client->batch([
            '101' => '/articles/101',
            '102' => '/articles/102'
        ]);

        $this->assertEquals($resp['101']->getData(), $body['responses']['101']['data']);
        $this->assertEquals($resp['102']->getData(), $body['responses']['102']['data']);
    }

    /**
     * @covers ::batchAsync
     */
    public function testBatchAsync()
    {
        $body = [
            'responses' => [
                '101' => [
                    'data' => [
                        'id' => 101,
                        'title' => 'Exercitationem illo quod et provident',
                        'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.'
                    ],
                    'meta' => [
                        'count' => 1
                    ],
                    'linked' => []
                ],
                '102' => [
                    'data' => [
                        'id' => 102,
                        'title' => 'Exercitationem illo quod et provident',
                        'content' => 'Duchess: flamingoes and mustard both bite. And the Eaglet bent down its head down.'
                    ],
                    'meta' => [
                        'count' => 1
                    ],
                    'linked' => []
                ]
            ]
        ];

        $client = $this->getMockClient([
            new Response(200, [], json_encode($body))
        ]);
        $promise = $client->batchAsync([
            '101' => '/articles/101',
            '102' => '/articles/102'
        ]);
        /** @var APIResponseInterface[] $resp */
        $resp = $promise->wait();

        $this->assertEquals($resp['101']->getData(), $body['responses']['101']['data']);
        $this->assertEquals($resp['102']->getData(), $body['responses']['102']['data']);
    }

    /**
     * @covers ::get
     * @covers ::getLastHTTPResponse
     */
    public function testGetLastHTTPResponse()
    {
        $lastResponse = new Response(200, [], json_encode([]));
        $client = $this->getMockClient([
            $lastResponse
        ]);
        $client->get('/articles');

        $this->assertSame($lastResponse, $client->getLastHTTPResponse());
    }

    /**
     * @covers ::get
     * @covers ::getLastHTTPRequest
     */
    public function testGetLastHTTPRequest()
    {
        $client = $this->getMockClient();
        $client->get('/articles');

        $this->assertInstanceOf(RequestInterface::class, $client->getLastHTTPRequest());
    }

    /**
     * @covers ::get
     * @covers ::getLastHTTPRequestException
     */
    public function testGetLastHTTPRequestException()
    {
        $client = $this->getMockClient([
            new Response(500, [], json_encode([]))
        ]);
        try {
            $client->get('/articles');
        } catch (\Exception $e) {}

        $this->assertNotNull($client->getLastHTTPRequestException());

        $client = $this->getMockClient();
        $client->get('/articles');

        $this->assertNull($client->getLastHTTPRequestException());
    }

    /**
     * @covers ::get
     */
    public function testGetThrowsAPIException()
    {
        $this->expectException(DeskPRO\API\Exception\APIException::class);
        $this->expectExceptionCode(500);

        $body = [
            'status'  => 500,
            'message' => 'error'
        ];
        $client = $this->getMockClient([
            new Response(500, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetThrowsMalformedResponseException()
    {
        $this->expectException(DeskPRO\API\Exception\MalformedResponseException::class);
        $this->expectExceptionCode(0);

        $client = $this->getMockClient([
            new Response(400, [], 'test')
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetProperRootExceptionMessage()
    {
        $this->expectException(DeskPRO\API\Exception\APIException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Request input is invalid: One or more of the given values is invalid.');

        $body = [
            'status' => 400,
            'code' => 'invalid_input',
            'message' => 'Request input is invalid',
            'errors' => [
                'errors' => [
                    [
                        'code' => 'bad_choice',
                        'message' => 'One or more of the given values is invalid.'
                    ]
                ]
            ]
        ];
        $client = $this->getMockClient([
            new Response(400, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetProperFieldExceptionMessage()
    {
        $this->expectException(DeskPRO\API\Exception\APIException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Request input is invalid: \'department\' One or more of the given values is invalid.');

        $body = [
            'status' => 400,
            'code' => 'invalid_input',
            'message' => 'Request input is invalid',
            'errors' => [
                'fields' => [
                    'department' => [
                        'errors' => [
                            [
                                'code' => 'bad_choice',
                                'message' => 'One or more of the given values is invalid.'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $client = $this->getMockClient([
            new Response(400, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetProperChildFieldExceptionMessage()
    {
        $this->expectException(DeskPRO\API\Exception\APIException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Request input is invalid: \'parent_field.child_field\' One or more of the given values is invalid.');

        $body = [
            'status' => 400,
            'code' => 'invalid_input',
            'message' => 'Request input is invalid',
            'errors' => [
                'fields' => [
                    'parent_field' => [
                        'fields' => [
                            'child_field' => [
                                'errors' => [
                                    [
                                        'code' => 'bad_choice',
                                        'message' => 'One or more of the given values is invalid.'
                                    ]
                                ]
                            ],
                        ],
                    ]
                ]
            ]
        ];
        $client = $this->getMockClient([
            new Response(400, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetThrowsAuthenticationException()
    {
        $this->expectException(DeskPRO\API\Exception\AuthenticationException::class);
        $this->expectExceptionCode(401);

        $body = [
            'status'  => 401,
            'message' => 'authentication'
        ];
        $client = $this->getMockClient([
            new Response(401, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetThrowsAccessDeniedException()
    {
        $this->expectException(DeskPRO\API\Exception\AccessDeniedException::class);
        $this->expectExceptionCode(403);

        $body = [
            'status'  => 403,
            'message' => 'access denied'
        ];
        $client = $this->getMockClient([
            new Response(403, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @covers ::get
     */
    public function testGetThrowsNotFoundException()
    {
        $this->expectException(DeskPRO\API\Exception\NotFoundException::class);
        $this->expectExceptionCode(404);

        $body = [
            'status'  => 404,
            'message' => 'not found'
        ];
        $client = $this->getMockClient([
            new Response(404, [], json_encode($body))
        ]);
        $client->get('/articles');
    }

    /**
     * @param Response[] $responses
     * @return DeskproClient
     */
    private function getMockClient(array $responses = [])
    {
        if (empty($responses)) {
            $responses[] = new Response(200, [], json_encode([]));
        }

        $mock       = new MockHandler($responses);
        $handler    = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client     = new DeskproClient('http://deskpro-dev.com', $httpClient);

        return $client;
    }
}
