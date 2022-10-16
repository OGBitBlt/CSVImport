<?php
namespace OGBitBlt\CSVImport\Validation;

class RowData
{
    private $columns = [];

    public function __construct(array $columns = [])
    {
        $this->columns = $columns;
    }

    public function addColumn(ColumnData $column) : self
    {
        array_push($this->columns, $column);
        return $this;
    }

    public function getColumns() : array
    {
        return $this->columns;
    }
}

?>