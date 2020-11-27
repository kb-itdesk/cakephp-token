<?php

declare(strict_types=1);

namespace Token\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Token\Model\Entity\Token;

/**
 * Create command.
 */
class CreateCommand extends Command
{
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
            'Creates token. Usage example: Token.Create "+1 year" \'{"key": "value"}\''
        )->addArgument('expire', [
            'required' => false,
            'help' => 'Token expiration date f.ex. "+1 year"'
        ])->addArgument('data', [
            'required' => false,
            'help' => 'Token data array in JSON format'
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
        $expire = $args->getArgument('expire');
        $tokenData = $args->getArgument('data') ? json_decode($args->getArgument('data'), true) : [];
        // debug($tokenData);exit;

        $token = \Token\Token::generate($tokenData, $expire);
        if ($token) {
            $io->out(__('Token {0} created', $token));
        } else {
            $io->out(__('Token creation error'));
        }
    }
}
