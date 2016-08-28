<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class SdkApplication extends Application
{
    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        // This should return the name of your command.
        return 'run';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new RunCommand();

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        $inputDefinition->setOptions([
            new InputOption('name', 'n', InputOption::VALUE_REQUIRED, 'Name of the component'),
            new InputOption('version', 'v', InputOption::VALUE_REQUIRED, 'Version of the component'),
            new InputOption('action', 'a', InputOption::VALUE_REQUIRED, 'Action of of the Service'),
            new InputOption('platform-version', 'p', InputOption::VALUE_REQUIRED, 'Version of the platform'),
            new InputOption('socket', 's', InputOption::VALUE_REQUIRED, 'Socket for ZeroMQ to listen'),
            new InputOption('debug', 'D', InputOption::VALUE_NONE, 'Debug mode'),
            new InputOption('var', 'V', InputOption::VALUE_REQUIRED, 'Input variable'),
        ]);

        return $inputDefinition;
    }
}
