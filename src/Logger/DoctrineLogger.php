<?php

declare(strict_types=1);

namespace App\Logger;

use Psr\Log\LoggerInterface;
use Stringable;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineLogger implements LoggerInterface
{
    private ?OutputInterface $output = null;
    private bool $showParams;
    private bool $showTypes;

    public function setOutput(OutputInterface $output, bool $showParams = false, bool $showTypes = false): void
    {
        $this->output = $output;
        $this->showParams = $showParams;
        $this->showTypes = $showTypes;
    }

    public function emergency(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement emergency() method.
    }

    public function alert(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement alert() method.
    }

    public function critical(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement critical() method.
    }

    public function error(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement error() method.
    }

    public function warning(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement warning() method.
    }

    public function notice(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement notice() method.
    }

    public function info(Stringable | string $message, array $context = []): void
    {
        // TODO: Implement info() method.
    }

    public function debug(Stringable | string $message, array $context = []): void
    {
        $this->printQuery($context['sql'], $context['params'] ?? null, $context['types'] ?? null);
    }

    public function log($level, Stringable | string $message, array $context = []): void
    {
        // TODO: Implement log() method.
    }

    private function printQuery($sql, ?array $params = null, ?array $types = null): void
    {
        if (null === $this->output) {
            return;
        }
        $this->output->writeln($sql);

        if ($this->showParams && $params) {
            $this->output->writeln('- params:');
            $this->output->writeln(print_r($params, true));
        }

        if ($this->showTypes && $types) {
            $this->output->writeln('- types:');
            $this->output->writeln(print_r($types, true));
        }
    }
}
