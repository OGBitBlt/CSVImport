<?php
namespace OGBitBlt\CSVImport\Importer;

use OGBitBlt\CSVImport\ImportParameters;
use OGBitBlt\CSVImport\ResultInfo;
use OGBitBlt\CSVImport\Validation\ValidationResults;
use OGBitBlt\CSVImport\Validation\ValidatorFacade;

class ImporterFacade
{
    private $params = null;

    public function __construct( ImportParameters $params )
    {
        $this->params = $params;
    }

    public function validate() : ValidationResults
    {
        return (new ValidatorFacade($this->params))->execute();
    }

    public function execute() : ImportResults
    {
        $results = new ImportResults();
        $db = new Database($this->params);
        if($this->params->getDatabaseParameters()->getCreateTemporaryTable() == true) {
            if($db->createTemporaryTable() == false) {
                $results->addResultInfo(new ResultInfo(0,'failed to create temporary table'));
                return $results;
            }
        }

        if($db->bulkLoad() == false) {
            $results->addResultInfo(new ResultInfo(1,'executing bulk load failed.'));
        }
        return $results;
    }
}