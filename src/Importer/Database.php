<?php
namespace OGBitBlt\CSVImport\Importer;

use mysqli;
use OGBitBlt\CSVImport\Enum\ColumnDataTypeEnum;
use OGBitBlt\CSVImport\Enum\CSVFormatTypeEnum;
use OGBitBlt\CSVImport\ImportParameters;

class Database
{
    private ?ImportParameters $params = null;
    
    public function __construct(ImportParameters $params) {
        $this->params = $params;
    }


    public function createTemporaryTable() : bool {
        $cmd = sprintf("DROP TABLE IF EXISTS %s;", $this->params->getDatabaseParameters()->getTemporaryTableName());
        $sqlResult = $this->execute_cmd($cmd);
        if(!$sqlResult) {
            printf("This fucking sucks\n");
        }
        $cmd = sprintf("CREATE TABLE %s ( ", $this->params->getDatabaseParameters()->getTemporaryTableName());
        $cmd = $cmd . "id INT PRIMARY KEY AUTO_INCREMENT";
        foreach($this->params->getValidationData()->getColumns() as $i => $columnData) {
            $cmd = $cmd . sprintf(", %s", $columnData->getHeader());
            switch($columnData->getDataType())
            {
                case ColumnDataTypeEnum::BOOLEAN: $cmd = $cmd . " BOOLEAN "; break;
                case ColumnDataTypeEnum::DATE: $cmd = $cmd . " DATETIME "; break;
                case ColumnDataTypeEnum::FLOAT: $cmd = $cmd . " FLOAT(11,7) "; break;
                case ColumnDataTypeEnum::INT: $cmd = $cmd . " INT "; break;
                case ColumnDataTypeEnum::STRING: $cmd = $cmd . " VARCHAR(255) "; break;
                case ColumnDataTypeEnum::TIMESTAMP: $cmd = $cmd . " TIMESTAMP "; break;
            }
            if(!$columnData->IsNullable()) $cmd = $cmd . "NOT NULL ";
        }
        $cmd = $cmd . ",import_status ENUM('imported','processed','invalid') NOT NULL,date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        $cmd = $cmd . ") ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
        //printf("Temporary Table Create SQL: \n%s\n", $cmd);
        return $this->execute_cmd($cmd);
    }

    public function bulkLoad() : bool  {
        $cmd = sprintf("LOAD DATA INFILE \"%s\" INTO TABLE %s", $this->params->getFile(), $this->params->getDatabaseParameters()->getTemporaryTableName());
        $cmd = $cmd . sprintf(" COLUMNS TERMINATED BY '%s'", $this->params->GetCSVFormat(CSVFormatTypeEnum::SEPARATOR));
        $cmd = $cmd . sprintf(" OPTIONALLY ENCLOSED BY '%s'", $this->params->GetCSVFormat(CSVFormatTypeEnum::ENCLOSER));
        $cmd = $cmd . sprintf(" ESCAPED BY '%s'", $this->params->GetCSVFormat(CSVFormatTypeEnum::ESCAPE));
        $cmd = $cmd . sprintf(" LINES TERMINATED BY '\\n'");
        $cmd = $cmd . sprintf(" IGNORE 1 ROW (");
        foreach($this->params->getValidationData()->getColumns() as $i => $columnData) {
            if($i > 0)$cmd = $cmd . ",";
            $cmd = $cmd . sprintf("%s", $columnData->getHeader());
        }
        $cmd = $cmd . sprintf(") SET import_status='imported', date=CURRENT_TIMESTAMP;");

        printf("Bulk Load SQL:\n%s\n", $cmd);

        return $this->execute_cmd($cmd);
    }

    private function execute_cmd(string $cmd) : bool 
    {
        $conn = new mysqli(
            $this->params->getDatabaseParameters()->getServerHost(), 
            $this->params->getDatabaseParameters()->getUserName(), 
            $this->params->getDatabaseParameters()->getPassword(),
            $this->params->getDatabaseParameters()->getDbName()
        );
        if($conn->connect_error) {
            printf("well fuck me raw!\n");
            return false;
        }
        $result = $conn->query($cmd);
        $conn->close();
        return $result;
    }
}
?>