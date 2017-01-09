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

namespace Katana\Sdk\Api;

/**
 * Support Transport Api class that encapsulates a list of transactions.
 * @package Katana\Sdk\Api
 */
class TransportTransactions
{
    /**
     * @var Transaction[]
     */
    private $transactions = [];

    /**
     * @param Transaction[] $transactions
     */
    public function __construct(array $transactions = [])
    {
        $this->transactions = $transactions;
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function add(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return true;
    }

    /**
     * @return bool
     */
    public function has()
    {
        return !empty($this->transactions);
    }

    /**
     * @param string $address
     * @param string $service
     * @return Transaction[]
     */
    public function get($address = '', $service = '')
    {
        $transactions = $this->transactions;

        if ($address) {
            $transactions = isset($transactions[$address]) ? $transactions[$address] : [];

            if ($service) {
                $transactions = isset($transactions[$service]) ? $transactions[$service] : [];
            }
        }

        return $transactions;
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function getArray($address = '', $service = '')
    {
        $transactions = [];
        foreach ($this->transactions as $transaction) {
            $origin = $transaction->getOrigin();
            if ($address && $origin->getAddress() !== $service) {
                continue;
            } elseif ($service && $origin->getName() !== $service) {
                continue;
            }

            $transactionOutput = [
                'a' => $transaction->getAction(),
                'p' => array_map(function (Param $param) {
                    return [
                        'n' => $param->getName(),
                        'v' => $param->getValue(),
                        't' => $param->getType(),
                    ];
                }, $transaction->getParams()),
            ];

            $type = $transaction->getType() === 'commit' ? 'c' : 'r';

            if ($service) {
                $transactions[$type][$origin->getVersion()][] = $transactionOutput;
            } elseif ($address) {
                $transactions[$type][$origin->getName()][$origin->getVersion()][] = $transactionOutput;
            } else {
                $transactions[$type][$origin->getAddress()][$origin->getName()][$origin->getVersion()][] = $transactionOutput;
            }
        }

        return $transactions;
    }
}
