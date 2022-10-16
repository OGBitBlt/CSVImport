<?php
namespace OGBitBlt\CSVImport\Validation;

use OGBitBlt\CSVImport\Enum\ColumnDataTypeEnum;
use OGBitBlt\CSVImport\Enum\CSVFormatTypeEnum;
use OGBitBlt\CSVImport\ImportParameters;
use OGBitBlt\CSVImport\ResultInfo;

class ValidatorFacade 
{
    private $params = null;

    public function __construct(ImportParameters $params)
    {
        $this->params = $params;
    }

    public function execute() : ValidationResults
    {
        $vresults = new ValidationResults();
        if(($fh = fopen($this->params->getFile(),'r')) !== FALSE){
            while(($data = fgetcsv(
                    $fh,
                    1000,
                    $this->params->GetCSVFormat(CSVFormatTypeEnum::SEPARATOR), 
                    $this->params->GetCSVFormat(CSVFormatTypeEnum::ENCLOSER), 
                    $this->params->GetCSVFormat(CSVFormatTypeEnum::ESCAPE)
                )) !== FALSE ) {
                $vresults->incrementProcessedCount();
                $pass = true;
                //printf("\n[TEST]: column_count\n");
                $pass = (count($data) == count($this->params->getValidationData()->getColumns()));
                if(!$pass){
                    $vresults->addResultInfo(
                        new ResultInfo(
                            $vresults->getLinesProcessedCount(), 
                            sprintf(
                                "expected %d columns but found %d instead.",
                                count($this->params->getValidationData()->getColumns()),
                                count($data)
                            )
                        )
                    );
                    if(!$this->params->getContinueOnError()) break;
                }
                foreach($this->params->getValidationData()->getColumns() as $i => $columnData) {
                    if($vresults->getLinesProcessedCount() == 1) {
                        // process the first line

                    } else {
                        if(isset($data[$i])) {
                            $field = $data[$i];
                            if($columnData->Normalize() != null) {
                                if($field == $columnData->Normalize()->From()){
                                    $field = $columnData->Normalize()->To();
                                }
                            }
                            
                        } else {
                            $field = null;
                        }

                        $pass = $this->null_test($field, $columnData, $vresults);
                        if($field != null && $field != '') {
                            $pass = $this->type_test($field,$columnData,$vresults);
                            $pass = $this->param_test($field, $columnData, $vresults);
                        }
                    }
                    //printf("\n");
                }
                if(!$pass) {
                    if(!$this->params->getContinueOnError()) break;
                }
                if(count($vresults->getResults()) >= 1000) break;
            }
        }
        fclose($fh);
        return $vresults;
    }

    private function param_test($field,$columnData,$results)
    {
        $pass = true;
        foreach($columnData->getParams() as $validationType => $validationValue)
        {
            switch($validationType)
            {
                case 'min_length': 
                    //printf("[TEST]:\tmin_string_length\t");
                    $pass = (strlen($field) >= intval($validationValue));
                    break;
                case 'max_length':
                    //printf("[TEST]:\tmax_string_length\t");
                    $pass = (strlen($field) <= intval($validationValue));
                    break;
                case 'length':
                    //printf("[TEST]:\tstring_length\t");
                    $pass = (strlen($field) == $validationValue);
                    break;
                case 'match_pattern':
                    //printf("[TEST]:\texpression_match\t");
                    $pass = preg_match($validationValue,$field);
                    break;
                case 'match':
                    //printf("[TEST]:\tstring_match\t");
                    $pass = ($field == $validationValue);
                    break;
                case 'numeric_between':
                    //printf("[TEST]:\tnumeric_between\t");
                    $pass = (intval($field) >= intval($validationValue['min']) && intval($field) <= intval($validationValue['max']));
                    break;
                case 'match_in':
                    //printf("[TEST]:\tmatch_in\t");
                    $pass = in_array($field,$validationValue);
                    break;
            }
            if(!$pass){
                $results->addResultInfo(
                    new ResultInfo(
                        $results->getLinesProcessedCount(),
                        sprintf(
                            "column %s failed validation (%s='%s') : '%s'",
                            $columnData->getHeader(),
                            $validationType,
                            $validationValue,
                            $field
                        )
                    )
                );
                if(!$this->params->getContinueOnError()) break;
            }
        }
        return $pass;
    }

    private function type_test($field,$columnData,$results)
    {
        $pass = true;
        //printf("[TEST]:\tfield_type\t");
        switch($columnData->getDataType())
        {
            case ColumnDataTypeEnum::BOOLEAN: 
                $pass = is_bool($field);
                break;
            case ColumnDataTypeEnum::DATE:
                $pass = strtotime($field);
                break;
            case ColumnDataTypeEnum::FLOAT:
                $pass = is_float(str_replace(',','',$field));
                break;
            case ColumnDataTypeEnum::INT:
                $pass = is_numeric(str_replace(',','',$field));
                break;
            case ColumnDataTypeEnum::STRING:
                $pass = is_string($field);
                break;
            case ColumnDataTypeEnum::TIMESTAMP:
                $pass = is_numeric($field);
                break;
        }

        if(!$pass) {
            $results->addResultInfo(
                new ResultInfo(
                    $results->getLinesProcessedCount(),
                    sprintf(
                        "column '%s' : (%s) is not of expected type %s",
                        $columnData->getHeader(),
                        $field,
                        $columnData->getDataType()->display_value()
                    )
                )
            );
        }

        return $pass;
    }

    private function null_test($field,$columnData,$results)
    {  
        //printf("[TEST]:\tnull_test\t");                  
        if(($field == '' || $field == null) && !$columnData->IsNullable()){
            $results->addResultInfo(
                new ResultInfo(
                    $results->getLinesProcessedCount(), 
                    sprintf(
                        "column %s is null",
                        $columnData->getHeader()
                    )
                )
            );
            return false;
        }
        return true;
    }
}
?>