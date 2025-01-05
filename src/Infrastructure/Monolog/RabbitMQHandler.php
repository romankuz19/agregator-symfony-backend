<?php

declare(strict_types=1);

namespace App\Infrastructure\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class RabbitMQHandler extends AbstractProcessingHandler
{
    public function __construct(private readonly ProducerInterface $producer, $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $message = json_encode([
            'level' => $record->level->getName(),
            'message' => $record->message,
            'context' => $record->context,
            'extra' => $record->extra,
            'timestamp' => (new \DateTime())->format(\DateTimeInterface::ATOM),
        ]);

        $this->producer->publish($message);
    }
}
