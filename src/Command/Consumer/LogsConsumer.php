<?php

declare(strict_types=1);

namespace App\Command\Consumer;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LogsConsumer implements ConsumerInterface
{
    private Logger $logger;

    public function __construct(ParameterBagInterface $params)
    {
        $this->logger = new Logger('rmq_logs');
        $socketHandler = new SocketHandler("tcp://{$params->get('logstash_host')}:{$params->get('logstash_port')}");
        $socketHandler->setFormatter(new JsonFormatter(ignoreEmptyContextAndExtra: true));
        $this->logger->pushHandler($socketHandler);
    }

    public function execute(AMQPMessage $msg): int
    {
        $logData = json_decode($msg->getBody(), true);

        if (!isset($logData['timestamp'])) {
            $logData['timestamp'] = (new \DateTime())->format(\DateTimeInterface::ATOM);
        }

        $logMessage = $logData['message'];
        $context = array_merge($logData['context'], [
            '@timestamp' => $logData['timestamp'],
        ]);

        $this->logger->log($logData['level'], $logMessage, $context);

        return ConsumerInterface::MSG_ACK;
    }
}
