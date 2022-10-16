<?php
namespace OGBitBlt\CSVImport;
/**
 * Simple object to store results line by line
 */
class ResultInfo
{
    /**
     * @var int $lineNumber the line the result info message pertains to 
     */
    private $lineNumber = 0;
    /**
     * @var string $message error info either during validation or import
     */
    private $message = "";

    /**
     * __construct 
     * @param int $lineNumber The line number the error occured on
     * @param string $message Info about the error that occured
     */
    public function __construct($lineNumber, $message)
    {
        $this->lineNumber = $lineNumber;
        $this->message = $message;
    }

    /**
     * @return the line number
     */
    public function getLineNumber() : int 
    {
        return $this->lineNumber;
    }

    /**
     * @return The error message
     */
    public function getMessage() : string 
    {
        return $this->message;
    }
}
?>