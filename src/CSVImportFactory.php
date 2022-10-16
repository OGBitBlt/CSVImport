<?php
namespace OGBitBlt\CSVImport;

use OGBitBlt\CSVImport\Importer\ImporterFacade;

class CSVImportFactory
{
    public static function Create(ImportParameters $importParameters) : ImporterFacade
    {
        return new ImporterFacade($importParameters);
    }
}
?>