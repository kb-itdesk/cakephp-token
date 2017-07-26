<?php
namespace Token\Model\Table;

use Cake\Chronos\Chronos;
use Cake\ORM\Table;
use Cake\Utility\Text;

class TokensTable extends Table
{
    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('token_tokens');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');

        $this->addBehavior('Timestamp');
    }

    /**
     * create token with option
     * @param  string       $scope   Scope or Model
     * @param  int          $scopeId scope id
     * @param  string       $type    token type (custom)
     * @param  null|date    $expire  expire date or null
     * @param  array        $value   token value (custom)
     * @return string                token id
     */
    public function newToken($scope, $scopeId, $type, $expire = null, array $value = [])
    {
        $entity = $this->newEntity([
            'id' => $this->uniqId(),
            'scope' => $scope,
            'scope_id' => $scopeId,
            'type' => $type,
            'content' => json_encode($value),
            'expire' => is_null($expire) ? Chronos::now() : Chronos::parse($expire),
        ]);

        $this->save($entity);

        return $entity->id;
    }

    /**
     * generate uniq token id
     * @return string
     */
    protected function uniqId()
    {
        $exists = true;

        while ($exists) {
            $key = $this->generateKey();
            $exists = $this->find()->where(['id' => $key])->first();
        }

        return $key;
    }

    /**
     * generate random key
     * @return string  8 chars key
     */
    protected function generateKey()
    {
        return substr(hash('sha256', Text::uuid()), 0, 8);
    }
}
