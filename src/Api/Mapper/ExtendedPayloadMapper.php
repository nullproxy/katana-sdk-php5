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

class ExtendedPayloadMapper implements PayloadMapperInterface
{
    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['name'],
            $param['value'],
            $param['type'],
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
            'name' => $param->getName(),
            'version' => $param->getValue(),
            'type' => $param->getType(),
        ];
    }

    /**
     * @param array $raw
     * @return Param[]
     */
    public function getParams(array $raw)
    {
        if (empty($raw['command']['arguments']['params'])) {
            return [];
        }

        $return = [];
        foreach ($raw['command']['arguments']['params'] as $param) {
            $return[] = new Param(
                $param['name'],
                $param['value'],
                $param['type'],
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
            'command_reply' => [
                'name' => 'test',
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
            'command_reply' => [
                'name' => 'test',
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
            'command_reply' => [
                'name' => 'test',
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
            $error['message'] = $message;
        }

        if ($code) {
            $error['code'] = $code;
        }

        if ($status) {
            $error['status'] = $status;
        }

        return ['error' => $error];
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
        $rawMeta = $raw['command']['arguments']['transport']['meta'];

        return new TransportMeta(
            $rawMeta['version'],
            $rawMeta['id'],
            $rawMeta['datetime'],
            $rawMeta['gateway'],
            $rawMeta['origin'],
            $rawMeta['level'],
            isset($rawMeta['properties'])? $rawMeta['properties'] : []
        );
    }

    /**
     * @param TransportMeta $meta
     * @param array $output
     * @return array
     */
    public function writeTransportMeta(TransportMeta $meta, array $output)
    {
        $output['command_reply']['result']['transport']['meta'] = [
            'version' => $meta->getVersion(),
            'id' => $meta->getId(),
            'datetime' => $meta->getDatetime(),
            'gateway' => $meta->getGateway(),
            'origin' => $meta->getOrigin(),
            'level' => $meta->getLevel(),
        ];

        if ($meta->hasProperties()) {
            $output['command_reply']['result']['transport']['meta']['properties'] = $meta->getProperties();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportFiles
     */
    public function getTransportFiles(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['files'])) {
            $data = $raw['command']['arguments']['transport']['files'];
        } else {
            $data = [];
        }

        foreach ($data as $service => $serviceFiles) {
            foreach ($serviceFiles as $version => $versionFiles) {
                foreach ($versionFiles as $action => $actionFiles) {
                    foreach ($actionFiles as $name => $fileData) {
                        $data[$service][$version][$action][$name] = new File(
                            $name,
                            $fileData['path'],
                            $fileData['mime'],
                            $fileData['filename'],
                            $fileData['size'],
                            $fileData['token']
                        );
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
        foreach ($files->getAll() as $service => $serviceFiles) {
            foreach ($serviceFiles as $version => $versionFiles) {
                foreach ($versionFiles as $action => $actionFiles) {
                    /** @var File $file */
                    foreach ($actionFiles as $name => $file) {
                        $output['command_reply']['response']['transport']['files'][$service][$version][$action][$name] = [
                            'path' => $file->getPath(),
                            'mime' => $file->getMime(),
                            'filename' => $file->getFilename(),
                            'size' => $file->getSize(),
                            'token' => $file->getToken(),
                        ];
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
        if (!isset($raw['command']['arguments']['transport']['body'])) {
            return null;
        }

        $rawBody = $raw['command']['arguments']['transport']['body'];

        return new File(
            'body',
            $rawBody['path'],
            $rawBody['mime'],
            $rawBody['filename'],
            $rawBody['size'],
            $rawBody['token']
        );
    }

    /**
     * @param File $body
     * @param array $output
     * @return array
     */
    public function writeTransportBody(File $body, array $output)
    {
        $output['command_reply']['response']['transport']['body'] = [
            'path' => $body->getPath(),
            'mime' => $body->getMime(),
            'filename' => $body->getFilename(),
            'size' => $body->getSize(),
            'token' => $body->getToken(),
        ];

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportData|null
     */
    public function getTransportData(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['data'])) {
            $data = $raw['command']['arguments']['transport']['data'];
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
        $output['command_reply']['response']['transport']['data'] = $data->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportRelations
     */
    public function getTransportRelations(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['relations'])) {
            $relations = $raw['command']['arguments']['transport']['relations'];
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
        $output['command_reply']['response']['transport']['relations'] = $relations->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportLinks
     */
    public function getTransportLinks(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['links'])) {
            $links = $raw['command']['arguments']['transport']['links'];
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
        $output['command_reply']['response']['transport']['links'] = $links->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportCalls
     */
    public function getTransportCalls(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['calls'])) {
            $rawCalls = $raw['command']['arguments']['transport']['calls'];
        } else {
            $rawCalls = [];
        }

        $calls = [];
        foreach ($rawCalls as $address => $addressCalls) {
            foreach ($addressCalls as $service => $serviceCalls) {
                foreach ($serviceCalls as $version => $versionCalls) {
                    $calls += array_map(function (array $callData) use ($address, $service, $version) {
                        return new Call(
                            new ServiceOrigin($service, $version),
                            $callData['name'],
                            new VersionString($callData['version']),
                            $callData['action'],
                            isset($callData['params'])? array_map([$this, 'getParam'], $callData['params']) : []
                        );
                    }, $versionCalls);
                }
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
                'name' => $call->getService(),
                'version' => $call->getVersion(),
                'action' => $call->getAction(),
            ];

            if ($call->getParams()) {
                $callData['params'] = array_map([$this, 'writeParam'], $call->getParams());
            }

            $output['command_reply']['response']['transport']['calls'][$call->getOrigin()->getAddress()][$call->getOrigin()->getName()][$call->getOrigin()->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportTransactions
     */
    public function getTransportTransactions(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['transactions'])) {
            $rawTransactions = $raw['command']['arguments']['transport']['transactions'];
        } else {
            $rawTransactions = [];
        }

        $transactions = [];
        foreach ($rawTransactions as $address => $addressTransactions) {
            foreach ($addressTransactions as $type => $typeTransactions) {
                $transactions += array_map(function ($transactionData) use ($address, $type) {
                    return new Transaction(
                        $type,
                        new ServiceOrigin($transactionData['service'], $transactionData['version']),
                        $transactionData['action'],
                        $transactionData['callee'],
                        isset($transactionData['params']) ? array_map([$this, 'getParam'], $transactionData['params']) : []
                    );
                }, $typeTransactions);
            }
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
                'service' => $transaction->getOrigin()->getName(),
                'version' => $transaction->getOrigin()->getVersion(),
                'action' => $transaction->getAction(),
                'callee' => $transaction->getCallee(),
            ];

            if ($transaction->getParams()) {
                $transactionData['params'] = array_map([$this, 'writeParam'], $transaction->getParams());
            } else {
                // todo: remove when katana makes parameters optional
                $transactionData['params'] = [];
            }

            $type = $transaction->getType();

            $output['command_reply']['response']['transport']['transactions'][$transaction->getOrigin()->getName()][$type][] = $transactionData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportErrors
     */
    public function getTransportErrors(array $raw)
    {
        if (isset($raw['command']['arguments']['transport']['errors'])) {
            $rawErrors = $raw['command']['arguments']['transport']['errors'];
        } else {
            $rawErrors = [];
        }

        $errors = [];
        foreach ($rawErrors as $service => $serviceErrors) {
            foreach ($serviceErrors as $version => $versionErrors) {
                $errors += array_map(function ($errorData) use ($service, $version) {
                    return new Error(
                        $service,
                        $version,
                        $errorData['message'],
                        $errorData['code'],
                        $errorData['status']
                    );
                }, $versionErrors);
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
            $output['command_reply']['response']['transport']['errors'][$error->getService()][$error->getVersion()][] = [
                'message' => $error->getMessage(),
                'code' => $error->getCode(),
                'status' => $error->getStatus(),
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
        $query = isset($raw['command']['arguments']['request']['query'])?
            $raw['command']['arguments']['request']['query'] : [];
        $postData = isset($raw['command']['arguments']['request']['post data'])?
            $raw['command']['arguments']['request']['post data'] : [];
        $headers = isset($raw['command']['arguments']['request']['headers'])?
            $raw['command']['arguments']['request']['headers'] : [];
        $body = isset($raw['command']['arguments']['request']['body'])?
            $raw['command']['arguments']['request']['body'] : '';

        $rawFiles = isset($raw['command']['arguments']['request']['files'])?
            $raw['command']['arguments']['request']['files'] : [];
        $files = array_map(function (array $fileData) {
            return new File(
                $fileData['name'],
                $fileData['path'],
                $fileData['mime'],
                $fileData['filename'],
                $fileData['size'],
                $fileData['token']
            );
        }, $rawFiles);

        return new HttpRequest(
            $raw['command']['arguments']['request']['version'],
            $raw['command']['arguments']['request']['method'],
            $raw['command']['arguments']['request']['url'],
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
        return $raw['command']['arguments']['meta']['protocol'];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayAddress(array $raw)
    {
        return $raw['command']['arguments']['meta']['gateway'][1];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getClientAddress(array $raw)
    {
        return $raw['command']['arguments']['meta']['client'];
    }

    /**
     * @param array $raw
     * @return HttpResponse
     */
    public function getHttpResponse(array $raw)
    {
        list($statusCode, $statusText) = explode(' ', $raw['command']['arguments']['response']['status'], 2);

        $headers = isset($raw['command']['arguments']['response']['headers'])?
            $raw['command']['arguments']['response']['headers'] : [];

        return new HttpResponse(
            $raw['command']['arguments']['response']['version'],
            new HttpStatus($statusCode, $statusText),
            $raw['command']['arguments']['response']['body'],
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
        if (isset($raw['command']['arguments']['call']['params'])) {
            foreach ($raw['command']['arguments']['call']['params'] as $param) {
                $params[] = $this->getParam($param);
            }
        }

        return new ServiceCall(
            $raw['command']['arguments']['call']['service'],
            new VersionString($raw['command']['arguments']['call']['version']),
            $raw['command']['arguments']['call']['action'],
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
        $output['command_reply']['response']['call'] = [
            'service' => $call->getService(),
            'version' => $call->getVersion(),
            'action' => $call->getAction(),
            'params' => array_map([$this, 'writeParam'], $call->getParams()),
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
        $output['command_reply']['response']['response'] = [
            'version' => $response->getProtocolVersion(),
            'status' => $response->getStatus(),
            'body' => $response->getBody(),
        ];

        if ($response->getHeaders()) {
            $output['command_reply']['response']['response']['headers'] = $response->getHeaders();
        }

        return $output;
    }
}
