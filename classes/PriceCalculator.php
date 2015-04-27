<?php

class PriceCalculator
{
    public $basePrice;
    public $steps;

    private $_defaultParams;

    public function __construct($totalOfStudent=1)
    {
        $priceConfig = Yii::app()->params['price'];
        if(isset($priceConfig['class1_'.$totalOfStudent])){
        	$config = $priceConfig['class1_'.$totalOfStudent];//Price Config by total of Student
        }else{
        	$config = $priceConfig['class1_1'];//Default price config table
        }
        $this->basePrice = $config['base_price'];
        $this->steps = $config['steps'];
        $this->_defaultParams = array('total_of_session'=>10, 'total_of_student'=>1, 'created_date'=>date("Y-m-d"));

    }

    public function calculate($params, $user = NULL)
    {
        $results = array('base_price'=>$this->basePrice, 'steps'=>array(), 'final_price'=>$this->basePrice);
        $currentPrice = $this->basePrice;
        $params = array_merge($this->_defaultParams, $params);
        foreach($this->steps as $step) {
            $obj = new $step['class'];
            $result = call_user_func(array($obj, $step['method']), $currentPrice, $params, $user);
            if($result) {
                $results['steps'][] = $result;
                $currentPrice = $result['next_price'];
                $results['final_price'] = $result['next_price'];
            }
        }
        $results['total_price'] = $params['total_of_session'] * $results['final_price'];
        return $results;
    }

}