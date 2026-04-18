<?php
/**
 * JSON-RPC / MCP fout-exception met code+message+data.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

use RuntimeException;

class McpException extends RuntimeException
{
    /** @var mixed|null */
    private $data;

    public function __construct(int $code, string $message, $data = null)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function toJsonRpc(): array
    {
        $out = ['code' => $this->getCode(), 'message' => $this->getMessage()];
        if ($this->data !== null) {
            $out['data'] = $this->data;
        }
        return $out;
    }
}
