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

namespace Katana\Sdk\Api\Mapper;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\Call;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Api\Error;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Protocol\Http\HttpStatus;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Api\ServiceCall;
use Katana\Sdk\Api\ServiceOrigin;
use Katana\Sdk\Api\Transaction;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;

class CompactPayloadMapper implements PayloadMapperInterface
{
    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['n'],
            $param['v'],
            $param['t'],
            true
        );
    }

    /**
     * @param Param $param
     * @return array
     */
    private function writeParam(Param $param)
    {
        return [
            'n' => $param->getName(),
            'v' => $param->getValue(),
            't' => $param->getType(),
        ];
    }

    /**
     * @param array $raw
     * @return Param[]
     */
    public function getParams(array $raw)
    {
        if (empty($raw['c']['a']['p'])) {
            return [];
        }

        $return = [];
        foreach ($raw['c']['a']['p'] as $param) {
            $return[] = new Param(
                $param['n'],
                $param['v'],
                $param['t'],
                true
            );
        }

        return $return;
    }

    /**
     * @param ActionApi $action
     * @return array
     */
    public function writeActionResponse(ActionApi $action)
    {
        $response = [
            'cr' => [
                'n' => $action->getName(),
            ],
        ];

        return $this->writeTransport($action->getTransport(), $response);
    }

    /**
     * @param RequestApi $request
     * @return array
     */
    public function writeRequestResponse(RequestApi $request)
    {
        $message = [
            'cr' => [
                'n' => $request->getName(),
            ],
        ];

        return $this->writeServiceCall($request->getServiceCall(), $message);
    }

    /**
     * @param ResponseApi $response
     * @return array
     */
    public function writeResponseResponse(ResponseApi $response)
    {
        $message = [
            'cr' => [
                'n' => $response->getName(),
            ],
        ];

        return $this->writeHttpResponse($response->getHttpResponse(), $message);
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return array
     */
    public function writeErrorResponse($message = '', $code = 0, $status = '')
    {
        $error = [];
        if ($message) {
            $error['m'] = $message;
        }

        if ($code) {
            $error['c'] = $code;
        }

        if ($status) {
            $error['s'] = $status;
        }

        return ['E' => $error];
    }

    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw)
    {
        return new Transport(
            $this->getTransportMeta($raw),
            $this->getTransportFiles($raw),
            $this->getTransportData($raw),
            $this->getTransportRelations($raw),
            $this->getTransportLinks($raw),
            $this->getTransportCalls($raw),
            $this->getTransportTransactions($raw),
            $this->getTransportErrors($raw),
            $this->getTransportBody($raw)
        );
    }

    /**
     * @param Transport $transport
     * @param array $output
     * @return array
     */
    public function writeTransport(Transport $transport, array $output)
    {
        $output = $this->writeTransportMeta($transport->getMeta(), $output);
        $output = $this->writeTransportFiles($transport->getFiles(), $output);
        $output = $this->writeTransportData($transport->getData(), $output);
        $output = $this->writeTransportRelations($transport->getRelations(), $output);
        $output = $this->writeTransportLinks($transport->getLinks(), $output);
        $output = $this->writeTransportCalls($transport->getCalls(), $output);
        $output = $this->writeTransportTransactions($transport->getTransactions(), $output);
        $output = $this->writeTransportErrors($transport->getErrors(), $output);
        if ($transport->hasBody()) {
            $output = $this->writeTransportBody($transport->getBody(), $output);
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportMeta
     */
    public function getTransportMeta(array $raw)
    {
        $rawMeta = $raw['c']['a']['T']['m'];

        return new TransportMeta(
            $rawMeta['v'],
            $rawMeta['i'],
            $rawMeta['d'],
            $rawMeta['g'],
            $rawMeta['o'],
            $rawMeta['l'],
            isset($rawMeta['p'])? $rawMeta['p'] : []
        );
    }

    /**
     * @param TransportMeta $meta
     * @param array $output
     * @return array
     */
    public function writeTransportMeta(TransportMeta $meta, array $output)
    {
        $output['cr']['r']['T']['m'] = [
            'v' => $meta->getVersion(),
            'i' => $meta->getId(),
            'd' => $meta->getDatetime(),
            'g' => $meta->getGateway(),
            'o' => $meta->getOrigin(),
            'l' => $meta->getLevel(),
        ];

        if ($meta->hasProperties()) {
            $output['cr']['r']['T']['m']['p'] = $meta->getProperties();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportFiles
     */
    public function getTransportFiles(array $raw)
    {
        if (isset($raw['c']['a']['T']['f'])) {
            $data = $raw['c']['a']['T']['f'];
        } else {
            $data = [];
        }

        foreach ($data as $address => $addressFiles) {
            foreach ($addressFiles as $service => $serviceFiles) {
                foreach ($serviceFiles as $version => $versionFiles) {
                    foreach ($versionFiles as $action => $actionFiles) {
                        foreach ($actionFiles as $name => $fileData) {
                            $token = isset($fileData['t']) ? $fileData['t'] : '';
                            $data[$address][$service][$version][$action][$name] = new File(
                                $name,
                                $fileData['p'],
                                $fileData['m'],
                                $fileData['f'],
                                $fileData['s'],
                                $token
                            );
                        }
                    }
                }
            }
        }

        return new TransportFiles($data);
    }

    /**
     * @param TransportFiles $files
     * @param array $output
     * @return array
     */
    public function writeTransportFiles(TransportFiles $files, array $output)
    {
        foreach ($files->getAll() as $address => $addressFiles) {
            foreach ($addressFiles as $service => $serviceFiles) {
                foreach ($serviceFiles as $version => $versionFiles) {
                    foreach ($versionFiles as $action => $actionFiles) {
                        /** @var File $file */
                        foreach ($actionFiles as $name => $file) {
                            $output['cr']['r']['T']['f'][$address][$service][$version][$action][$name] = [
                                'p' => $file->getPath(),
                                'm' => $file->getMime(),
                                'f' => $file->getFilename(),
                                's' => $file->getSize(),
                                't' => $file->getToken(),
                            ];
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return File|null
     */
    public function getTransportBody(array $raw)
    {
        if (!isset($raw['c']['a']['T']['b'])) {
            return null;
        }

        $rawBody = $raw['c']['a']['T']['b'];

        return new File(
            'body',
            $rawBody['p'],
            $rawBody['m'],
            $rawBody['f'],
            $rawBody['s'],
            $rawBody['t']
        );
    }

    /**
     * @param File $body
     * @param array $output
     * @return array
     */
    public function writeTransportBody(File $body, array $output)
    {
        $output['cr']['r']['T']['b'] = [
            'p' => $body->getPath(),
            'm' => $body->getMime(),
            'f' => $body->getFilename(),
            's' => $body->getSize(),
            't' => $body->getToken(),
        ];

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportData|null
     */
    public function getTransportData(array $raw)
    {
        if (isset($raw['c']['a']['T']['d'])) {
            $data = $raw['c']['a']['T']['d'];
        } else {
            $data = [];
        }

        return new TransportData($data);
    }

    /**
     * @param TransportData $data
     * @param array $output
     * @return array
     */
    public function writeTransportData(TransportData $data, array $output)
    {
        if ($data->get()) {
            $output['cr']['r']['T']['d'] = $data->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportRelations
     */
    public function getTransportRelations(array $raw)
    {
        if (isset($raw['c']['a']['T']['r']) && (array) $raw['c']['a']['T']['r']) {
            $relations = $raw['c']['a']['T']['r'];
        } else {
            $relations = [];
        }

        return new TransportRelations($relations);
    }

    /**
     * @param TransportRelations $relations
     * @param array $output
     * @return array
     */
    public function writeTransportRelations(TransportRelations $relations, array $output)
    {
        if ($relations->get()) {
            $output['cr']['r']['T']['r'] = $relations->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportLinks
     */
    public function getTransportLinks(array $raw)
    {
        if (isset($raw['c']['a']['T']['l']) && (array) $raw['c']['a']['T']['l']) {
            $links = $raw['c']['a']['T']['l'];
        } else {
            $links = [];
        }

        return new TransportLinks($links);
    }

    /**
     * @param TransportLinks $links
     * @param array $output
     * @return array
     */
    public function writeTransportLinks(TransportLinks $links, array $output)
    {
        if ($links->get()) {
            $output['cr']['r']['T']['l'] = $links->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportCalls
     */
    public function getTransportCalls(array $raw)
    {
        if (isset($raw['c']['a']['T']['c']) && (array) $raw['c']['a']['T']['c']) {
            $rawCalls = $raw['c']['a']['T']['c'];
        } else {
            $rawCalls = [];
        }

        $calls = [];
        foreach ($rawCalls as $service => $serviceCalls) {
            foreach ($serviceCalls as $version => $versionCalls) {
                $calls += array_map(function (array $callData) use ($service, $version) {
                    return new Call(
                        new ServiceOrigin('', $service, $version),
                        $callData['n'],
                        new VersionString($callData['v']),
                        $callData['a'],
                        isset($callData['p'])? array_map([$this, 'getParam'], $callData['p']) : []
                    );
                }, $versionCalls);
            }
        }

        return new TransportCalls($calls);
    }

    /**
     * @param TransportCalls $calls
     * @param array $output
     * @return array
     */
    public function writeTransportCalls(TransportCalls $calls, array $output)
    {
        foreach ($calls->get() as $call) {
            $callData = [
                'n' => $call->getService(),
                'v' => $call->getVersion(),
                'a' => $call->getAction(),
            ];

            if ($call->getParams()) {
                $callData['p'] = array_map([$this, 'writeParam'], $call->getParams());
            } else {
                $callData['p'] = [];
            }

            $output['cr']['r']['T']['c'][$call->getOrigin()->getName()][$call->getOrigin()->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportTransactions
     */
    public function getTransportTransactions(array $raw)
    {
        if (isset($raw['c']['a']['T']['t'])) {
            $rawTransactions = $raw['c']['a']['T']['t'];
        } else {
            $rawTransactions = [];
        }

        $transactions = [];
        foreach ($rawTransactions as $type => $typeTransactions) {
            $transactions = array_merge($transactions, array_map(function ($transactionData) use ($type) {
                $type = [
                    'c' => 'commit',
                    'r' => 'rollback',
                    'C' => 'complete',
                ][$type];

                return new Transaction(
                    $type,
                    new ServiceOrigin('', $transactionData['n'], $transactionData['v']),
                    $transactionData['a'],
                    isset($transactionData['p']) ? array_map([$this, 'getParam'], $transactionData['p']) : []
                );
            }, $typeTransactions));
        }

        return new TransportTransactions($transactions);
    }

    /**
     * @param TransportTransactions $transactions
     * @param array $output
     * @return array
     */
    public function writeTransportTransactions(TransportTransactions $transactions, array $output)
    {
        foreach ($transactions->get() as $transaction) {
            $transactionData = [
                's' => $transaction->getOrigin()->getName(),
                'v' => $transaction->getOrigin()->getVersion(),
                'a' => $transaction->getAction(),
            ];

            if ($transaction->getParams()) {
                $transactionData['p'] = array_map([$this, 'writeParam'], $transaction->getParams());
            } else {
                // todo: remove when katana makes parameters optional
                $transactionData['p'] = [];
            }

            $type = [
                'commit' => 'c',
                'rollback' => 'r',
                'complete' => 'C',
            ][$transaction->getType()];

            $output['cr']['r']['T']['t'][$type][] = $transactionData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportErrors
     */
    public function getTransportErrors(array $raw)
    {
        if (isset($raw['c']['a']['T']['e'])) {
            $rawErrors = $raw['c']['a']['T']['e'];
        } else {
            $rawErrors = [];
        }

        $errors = [];
        foreach ($rawErrors as $address => $addressErrors) {
            foreach ($addressErrors as $service => $serviceErrors) {
                foreach ($serviceErrors as $version => $versionErrors) {
                    $errors += array_map(function ($errorData) use ($address, $service, $version) {
                        return new Error(
                            $address,
                            $service,
                            $version,
                            $errorData['m'],
                            $errorData['c'],
                            $errorData['s']
                        );
                    }, $versionErrors);
                }
            }
        }

        return new TransportErrors($errors);
    }

    /**
     * @param TransportErrors $errors
     * @param array $output
     * @return array
     */
    public function writeTransportErrors(TransportErrors $errors, array $output)
    {
        foreach ($errors->get() as $error) {
            $output['cr']['r']['T']['e'][$error->getAddress()][$error->getService()][$error->getVersion()][] = [
                'm' => $error->getMessage(),
                'c' => $error->getCode(),
                's' => $error->getStatus(),
            ];
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return HttpRequest
     */
    public function getHttpRequest(array $raw)
    {
        $query = isset($raw['c']['a']['r']['q'])?
            $raw['c']['a']['r']['q'] : [];
        $postData = isset($raw['c']['a']['r']['p'])?
            $raw['c']['a']['r']['p'] : [];
        $headers = isset($raw['c']['a']['r']['h'])?
            $raw['c']['a']['r']['h'] : [];
        $body = isset($raw['c']['a']['r']['b'])?
            $raw['c']['a']['r']['b'] : '';

        $rawFiles = isset($raw['c']['a']['r']['f'])?
            $raw['c']['a']['r']['f'] : [];
        $files = array_map(function (array $fileData) {
            return new File(
                $fileData['n'],
                $fileData['p'],
                $fileData['m'],
                $fileData['f'],
                $fileData['s'],
                $fileData['t']
            );
        }, $rawFiles);

        return new HttpRequest(
            $raw['c']['a']['r']['v'],
            $raw['c']['a']['r']['m'],
            $raw['c']['a']['r']['u'],
            $query,
            $postData,
            $headers,
            $body,
            $files
        );
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayProtocol(array $raw)
    {
        return $raw['c']['a']['m']['p'];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayAddress(array $raw)
    {
        return $raw['c']['a']['m']['g'][1];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getClientAddress(array $raw)
    {
        return $raw['c']['a']['m']['c'];
    }

    /**
     * @param array $raw
     * @return HttpResponse
     */
    public function getHttpResponse(array $raw)
    {
        list($statusCode, $statusText) = explode(' ', $raw['c']['a']['R']['s'], 2);

        $headers = isset($raw['c']['a']['R']['h'])?
            $raw['c']['a']['R']['h'] : [];

        return new HttpResponse(
            $raw['c']['a']['R']['v'],
            new HttpStatus($statusCode, $statusText),
            $raw['c']['a']['R']['b'],
            $headers
        );
    }

    /**
     * @param array $raw
     * @return ServiceCall
     */
    public function getServiceCall(array $raw)
    {
        $params = [];
        if (isset($raw['c']['a']['c']['p'])) {
            foreach ($raw['c']['a']['c']['p'] as $param) {
                $params[] = $this->getParam($param);
            }
        }

        return new ServiceCall(
            $raw['c']['a']['c']['s'],
            new VersionString($raw['c']['a']['c']['v']),
            $raw['c']['a']['c']['a'],
            $params
        );
    }

    /**
     * @param ServiceCall $call
     * @param array $output
     * @return array
     */
    public function writeServiceCall(ServiceCall $call, array $output)
    {
        $output['cr']['r']['c'] = [
            's' => $call->getService(),
            'v' => $call->getVersion(),
            'a' => $call->getAction(),
            'p' => array_map([$this, 'writeParam'], $call->getParams()),
        ];

        return $output;
    }

    /**
     * @param HttpResponse $response
     * @param array $output
     * @return array
     */
    public function writeHttpResponse(HttpResponse $response, array $output)
    {
        $output['cr']['r']['R'] = [
            'v' => $response->getProtocolVersion(),
            's' => $response->getStatus(),
            'b' => $response->getBody(),
        ];

        if ($response->getHeaders()) {
            $output['cr']['r']['R']['h'] = $response->getHeaders();
        }

        return $output;
    }
}
