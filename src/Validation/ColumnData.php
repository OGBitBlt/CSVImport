<?php
namespace OGBitBlt\CSVImport\Validation;

use OGBitBlt\CSVImport\Enum\ColumnDataTypeEnum;

class ColumnData
{
    private string $header = "";
    private ColumnDataTypeEnum $datatype;
    private bool $isNullable = true;
    private array $params = [];
    private ?Normalize $normalize = null;

    public function __construct(
        string $header, 
        ColumnDataTypeEnum $datatype, 
        bool $isNullable = true, 
        array $params = [],
        Normalize $normalize = null
        )
    {
        $this->header = $header;
        $this->datatype = $datatype;
        $this->isNullable = $isNullable;
        $this->params = $params;
        $this->normalize = $normalize;
    }

    public function Normalize() : ?Normalize
    {
        return $this->normalize;
    }

    public function getParams() : array
    {
        return $this->params;
    }

    public function getHeader() : string 
    {
        return $this->header;
    }

    public function getDataType() : ColumnDataTypeEnum
    {
        return $this->datatype;
    }

    public function IsNullable() : bool
    {
        return $this->isNullable;
    }
}
?>