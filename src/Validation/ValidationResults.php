<?php
namespace OGBitBlt\CSVImport\Validation;

use Exception;
use OGBitBlt\CSVImport\ResultInfo;

class ValidationResults
{
    private array $results = [];
    private bool $isValid = true;
    private int $linesProcessed = 0;

    public function __construct()
    {
    }

    public function incrementProcessedCount() : int 
    {
        $this->linesProcessed++;
        return $this->linesProcessed;
    }

    public function getLinesProcessedCount() : int 
    {
        return $this->linesProcessed;
    }

    public function getResults() : array 
    {
        return $this->results;
    }

    public function addResultInfo(ResultInfo $resultInfo) : self
    {
        array_push($this->results, $resultInfo);
        $this->isValid = false;
        return $this;
    }

    public function  IsValid() : bool 
    {
        return $this->isValid;
    }
}
?>