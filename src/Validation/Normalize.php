<?php
namespace OGBitBlt\CSVImport\Validation;
class Normalize
{
    private $from = null;
    private $to = null;

    public function __construct(mixed $from, mixed $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function From() : mixed
    {
        return $this->from;
    }

    public function To() : mixed
    {
        return $this->to;
    }
}
?>