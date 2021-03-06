<?php
namespace Token\Test\TestCase;

use Token\Token;
use Cake\TestSuite\TestCase;

/**
 * Token\Model\Table\TokensTable Test Case
 */
class TokenTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Token.Tokens',
    ];

    public function testReadExpired()
    {
        $entity = Token::read('abcde456');
        $this->assertNull($entity);
    }

    public function testReadData()
    {
        $entity = Token::read('abcde789');
        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('email', $entity->content);
        $this->assertSame('erwane@phea.fr', $entity->content['email']);
    }

    public function testGenerate()
    {
        // no data at all
        $id = Token::generate();
        $entity = Token::read($id);
        $this->assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        $this->assertEmpty($entity->content);

        // content as array
        $id = Token::generate([
            'model' => 'Users',
            'model_id' => 1,
            'type' => 'accountValidation',
        ]);
        $entity = Token::read($id);
        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('model', $entity->content);
    }

    public function testDelete()
    {
        // expired token
        $result = Token::delete('abcde456');
        $this->assertFalse($result);

        // exist token
        $result = Token::delete('abcde789');
        $this->assertTrue($result);

        // Check if deleted
        $entity = Token::read('abcde789');
        $this->assertNull($entity);
    }
}
