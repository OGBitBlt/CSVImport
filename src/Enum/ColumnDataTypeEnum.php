<?php
namespace OGBitBlt\CSVImport\Enum;
enum ColumnDataTypeEnum
{
    case STRING;
    case INT;
    case DATE;
    case FLOAT;
    case BOOLEAN;
    case TIMESTAMP;
    public function display_value() {
        switch($this)
        {
            case ColumnDataTypeEnum::BOOLEAN: return "BOOLEAN";
            case ColumnDataTypeEnum::DATE: return "DATE";
            case ColumnDataTypeEnum::FLOAT: return "FLOAT";
            case ColumnDataTypeEnum::INT: return "INTEGER";
            case ColumnDataTypeEnum::STRING: return "STRING";
            case ColumnDataTypeEnum::TIMESTAMP: return "TIMESTAMP";
        }
    }
}
?>