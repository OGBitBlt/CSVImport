<?php
namespace OGBitBlt\CSVImport;

use Exception;
use OGBitBlt\CSVImport\Enum\CSVFormatTypeEnum;
use OGBitBlt\CSVImport\Validation\ColumnData;
use OGBitBlt\CSVImport\Validation\RowData;

class ImportParameters
{
    private bool $continueOnError = false;
    private ?RowData $validationData = null;
    private ?CSVFileInfo $fileInfo = null;
    private ?DatabaseParameters $dbParams = null;

    public function __construct(string $csvFile, RowData $validationData = null, string $separator = ",", string $enclosure = "\"", string $escape = "\\")
    {
        $this->setFile($csvFile);
        $this->validationData = $validationData;
        $this->fileInfo->setFieldSeparator($separator);
        $this->fileInfo->setFieldEnclosure($enclosure);
        $this->fileInfo->setEscapeCharacter($escape);
    }

    public function setDatabaseParameters(DatabaseParameters $dbParams) : self {
        $this->dbParams = $dbParams;
        return $this;
    }

    public function getDatabaseParameters() : DatabaseParameters {
        return $this->dbParams;
    }

    public function setFile(string $csvFile, string $separator = ",", string $enclosure = "\"", string $escape = "\\") : self
    {
        if(!file_exists($csvFile)) throw new Exception(sprintf("cannot locate file %s",$csvFile));
        if(!is_readable($csvFile)) throw new Exception(sprintf("file '%s' is not readable",$csvFile));
        if($this->fileInfo == null) $this->fileInfo = new CSVFileInfo($csvFile);
        else $this->fileInfo->setFile($csvFile);
        return $this;
    }

    public function getFile() : string
    {
        return $this->fileInfo->getFile();
    }

    public function setContinueOnError(bool $continueOnError) : self
    {
        $this->continueOnError = $continueOnError;
        return $this;
    }

    public function getContinueOnError() : bool 
    {
        return $this->continueOnError;
    }

    public function getValidationData() : ?RowData
    {
        return $this->validationData;
    }

    public function addValidationData(ColumnData $column)  : self
    {
        if($this->validationData == null) $this->validationData = new RowData();
        $this->validationData->addColumn($column);
        return $this;
    }

    public function GetCSVFormat(CSVFormatTypeEnum $formatType) : string 
    {
        switch($formatType)
        {
            case CSVFormatTypeEnum::ENCLOSER: return $this->fileInfo->getFieldEnclosure();
            case CSVFormatTypeEnum::ESCAPE: return $this->fileInfo->getEscapeCharacter();
            case CSVFormatTypeEnum::SEPARATOR: return $this->fileInfo->getFieldSeparator();
        }
        return "";
    }


}
?>