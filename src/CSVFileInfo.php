<?php
namespace OGBitBlt\CSVImport;
class CSVFileInfo
{
    private string $separator = ",";
    private string $enclosure = "\"";
    private string $escape = "\\";
    private string $csvFile = "";
    private bool $firstLineIsHeader = true;

    public function __construct(
        string $fileLocation, 
        bool $isFirstLineHeader = true,
        string $fieldSeparator = ",",
        string $fieldEnclosure = "\"",
        string $escapeCharacter = "\\" 
        )
    {
        $this->csvFile = $fileLocation;
        $this->firstLineIsHeader = $isFirstLineHeader;
        $this->separator = $fieldSeparator;
        $this->enclosure = $fieldEnclosure;
        $this->escape = $escapeCharacter;
    }

    public function getFile() : string {
        return $this->csvFile;
    }

    public function setFile(string $filePath) : self {
        $this->csvFile = $filePath;
        return $this;
    }

    public function setIsFirstLineHeader(bool $isFirstLineHeader) : self {
        $this->firstLineIsHeader = $isFirstLineHeader;
        return $this;
    }

    public function IsFirstLineHeader() : bool {
        return $this->firstLineIsHeader;
    }

    public function setFieldSeparator(string $fieldSeparator) : self {
        $this->separator = $fieldSeparator;
        return $this;
    }

    public function getFieldSeparator() : string  {
        return $this->separator;
    }

    public function setFieldEnclosure(string $fieldEnclosure) : self {
        $this->enclosure = $fieldEnclosure;
        return $this;
    }

    public function getFieldEnclosure() : string {
        return $this->enclosure;
    }

    public function setEscapeCharacter(string $escapeCharacter) : self {
        $this->escape = $escapeCharacter;
        return $this;
    }

    public function getEscapeCharacter() : string {
        return $this->escape;
    }
}
?>