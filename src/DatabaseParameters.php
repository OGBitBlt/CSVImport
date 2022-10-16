<?php
namespace OGBitBlt\CSVImport;
class DatabaseParameters 
{
    private bool $createTemporaryTable = false;
    private string $tableName = "bulkcsvimport";
    private string $connectionString = "";
    private string $serverHost="localhost";
    private string $userName="";
    private string $password = "";
    private string $dbName="";

    public function getConnectionString() : string {
        return $this->connectionString;
    }

    public function setConnectionString(string $connectionString) : self {
        $this->connectionString = $connectionString;
        return $this;
    }

    public function getCreateTemporaryTable() : bool {
        return $this->createTemporaryTable;
    }

    public function setCreateTemporaryTable(bool $createTemporaryTable) : self {
        $this->createTemporaryTable = $createTemporaryTable;
        return $this;
    }

    public function getTemporaryTableName() : string {
        return $this->tableName;
    }

    public function setTemporaryTableName(string $tableName) : self {
        $this->tableName = $tableName;
        return $this;
    }

    public function getServerHost() : string {
        return $this->serverHost;
    }

    public function setServerHost(string $serverHost) : self {
        $this->serverHost = $serverHost;
        return $this;
    }

    public function getUserName() : string {
        return $this->userName;
    }

    public function setUserName(string $userName) : self {
        $this->userName = $userName;
        return $this;
    }

    public function setPassword(string $password) : self {
        $this->password = $password;
        return $this;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function setDbName(string $dbName) : self {
        $this->dbName = $dbName;
        return $this;
    }

    public function getDbName() : string {
        return $this->dbName;
    }


}
?>