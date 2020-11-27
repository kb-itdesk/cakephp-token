<?php

declare(strict_types=1);

namespace Token\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Delete command.
 */
class DeleteCommand extends Command
{

    protected $modelClass = 'Token.Tokens';

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription(
            'Deletes given token. Usage example: Token.Delete "ulzo1zM64idoD9OlPFGDvIKvPvRVVvO3KiF17nqKQsGFbJQTXOhCvY5hKIHXDrxqsvAaNw0TWhL0igcugvD5FKEa1Oa3goiWPTMgWBqG4MDMxvWIAgnAjuBj0Y3noyK9"'
        )->addArgument('token', [
            'required' => true,
            'help' => 'Token to delete'
        ]);
        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $tokenString = $args->getArgument('token');
        $token = $this->Tokens->find('all', [
            'conditions' => [
                'id' => $tokenString
            ]
        ])->first();
        if ($token) {
            $this->Tokens->delete($token);
            $io->out(__('Token deleted'));
        } else {
            $io->out(__('Token does not exist'));
        }
    }
}
