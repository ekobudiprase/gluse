<?php

error_reporting(E_ERROR);
define("_RAND_MAX",32767);
define("HI",0.9);
define("LO",0.1);

class Backpropagation{
    public $output = null; // Output of each neuron
    public $vectorOutput = null; // Last calcualted output value
    public $delta = null;  // delta error value for each neuron 
    public $weight = null;  // Array of weights for each neuron 
    public $jmlLayer = null;  // Num of layers in the net, including input layer 
    public $layersSize = null; // Array num elments containing size for each layer 
    public $beta = null; // Learning rate 
    public $alpha = null; // Momentum 
    public $prevDwt = null; // Storage for weight-change made in previous epoch (three-dimensional array) 
    public $data = null; // Data 
    public $testData = null; // Test Data 
    public $numPattern = null; // N lines of Data 
    public $numInput = null; // N columns in Data 
    public $minX = 0; // Minimum value in data set 
    public $maxX = 1; // Maximum value in data set 
    public $normalizeMax = null; // Stores ann scale calculated parameters 
    public $normalizeMin = null;
    public $output_vector = null; // Holds all output data in one array 

    public function set($jmlLayer,$layersSize,$beta,$alpha,$minX,$maxX,$epoch,$treshold){
		$this->alpha = $alpha;
        $this->beta = $beta;
        $this->minX = $minX;
        $this->maxX = $maxX;
        $this->epoch = $epoch;
        $this->treshold = $treshold;

        // Set no of layers and their sizes
        $this->jmlLayer = $jmlLayer;
        $this->layersSize = $layersSize;
	}

	public function createWeight(){
        for($i=1; $i<$this->jmlLayer; $i++){
            for($j=0; $j<$this->layersSize[$i]; $j++){
                for($k=0; $k<$this->layersSize[$i-1]+1; $k++){
                    $this->weight[$i][$j][$k] = $this->get_random();
                }
                			
                $this->weight[$i][$j][$this->layersSize[$i-1]] = -1; 
            }
        }

        for($i=1; $i<$this->jmlLayer; $i++){
            for($j=0; $j<$this->layersSize[$i]; $j++){
                for($k=0; $k<$this->layersSize[$i-1]+1; $k++){					
                    $this->prevDwt[$i][$j][$k] = (double)0.0;
                }				
            }
        }

	}

    public function get_random(){
        $randValue = LO + (HI - LO) * mt_rand(0, _RAND_MAX)/_RAND_MAX;  
        return $randValue; //32767   
    }

    public function mse($target){	
        $mse = 0;
        
        for($i=0; $i<$this->layersSize[$this->jmlLayer-1]; $i++){
            $mse+=($target-$this->output[$this->jmlLayer-1][$i])*($target-$this->output[$this->jmlLayer-1][$i]);
        }
        return $mse/2;
    }

    /* ---	returns i'th outputput of the net */
    public function Out($i){
        return $this->output[$this->jmlLayer-1][$i];
    }
    
    /* ---
     * Feed forward one set of input
     * to update the output values for each neuron. This function takes the input 
     * to the net and finds the output of each neuron
     */
    public function feedForward($inputSource){	
        $sum = 0.0;
        
        $numElem = count($inputSource);
        //	assign content to input layer
        for($i=0; $i<$numElem; $i++){
            $this->output[0][$i] = $inputSource[$i];	
        }

        for($i=1;$i<$this->jmlLayer;$i++){
            for($j=0; $j<$this->layersSize[$i]; $j++){ 
                $sum = 0.0;
                for($k = 0;$k<$this->layersSize[$i-1]; $k++){
                    $sum += $this->output[$i-1][$k] * $this->weight[$i][$j][$k];	
                }

                $sum += $this->weight[$i][$j][$this->layersSize[$i-1]];
                $this->output[$i][$j] = $this->sigmoid($sum);						
            }
        }	
    }

    public function sigmoid($inputSource){
        return (double)(1.0 / (1.0 + exp(-$inputSource)));  
    }

    /* ---	Backpropagate errors from outputput	layer back till the first hidden layer */
    public function prosesBackpropagation($inputSource,$target){	
        /* ---	Update the output values for each neuron */
        $this->feedForward($inputSource);

        for($i=0;$i<$this->layersSize[$this->jmlLayer-1];$i++){    
            $this->delta[$this->jmlLayer-1][$i]=$this->output[$this->jmlLayer-1][$i]*(1-$this->output[$this->jmlLayer-1][$i])*($target-$this->output[$this->jmlLayer-1][$i]);
        }
        
        for($i=$this->jmlLayer-2;$i>0;$i--){
            for($j=0; $j<$this->layersSize[$i]; $j++){
                $sum=0.0;
                for($k=0; $k<$this->layersSize[$i+1]; $k++){
                    $sum += $this->delta[$i+1][$k]*$this->weight[$i+1][$k][$j];
                }			
                $this->delta[$i][$j] = $this->output[$i][$j]*(1-$this->output[$i][$j])*$sum;
            }
        }

        for($i=1; $i<$this->jmlLayer; $i++){
            for($j=0;$j<$this->layersSize[$i];$j++){
                for($k=0; $k<$this->layersSize[$i-1]; $k++){
                    $this->prevDwt[$i][$j][$k] = $this->beta*$this->delta[$i][$j]*$this->output[$i-1][$k];
                    $this->weight[$i][$j][$k] += $this->prevDwt[$i][$j][$k];
                }
                /* --- Apply the corrections */
                $this->prevDwt[$i][$j][$this->layersSize[$i-1]] = $this->beta*$this->delta[$i][$j];
                $this->weight[$i][$j][$this->layersSize[$i-1]] += $this->prevDwt[$i][$j][$this->layersSize[$i-1]];
            }
        }
    }

    /* --- Set scaling parameters */
    public function setScaleOutput($data){
        $oldMin = $data[0][0];
        $oldMax = $oldMin;	
        $numElem = count($data[0]);
        
        /* --- First calcualte minimum and maximum */
        for($i=0; $i<$this->numPattern; $i++){
            $oldMin = $data[$i][0];
            $oldMax = $oldMin;	
            
            for($j=1; $j<$numElem; $j++){
                // Min
                if($oldMin > $data[$i][$j]){
                    $oldMin = $data[$i][$j];
                }
                // Max
                if($oldMax < $data[$i][$j]){
                    $oldMax = $data[$i][$j];
                }
            }
            $this->normalizeMin[$i] = $oldMin;
            $this->normalizeMax[$i] = $oldMax;
        }
        
    }

    public function scale($data, $min, $max){
        $this->setScaleOutput($data);
        $numElem = count($data[0]);
        $temp = 0.0;

        for( $i=0; $i < $this->numPattern; $i++ ){
            for($j=0; $j<$numElem; $j++){
                $temp = (HI-LO)*(($data[$i][$j] - $min) / ($max - $min)) + LO;
                $data[$i][$j] = $temp;
            }
        }

        return $data;
    }

    public function unscaleOutput($output_vector, $min, $max){	
	    $temp=0.0;

	    $temp = ($max-$min) * (($output_vector - LO) / (HI-LO)) + $min ;	
	    $unscaledVector = $temp;
        
	    return $unscaledVector;
    }

    public function Run($param){
        // echo '<pre>'; print_r($param); 
        $treshold =  $this->treshold;
        $numEpoch = $this->epoch;	
        $MSE = 0.0;	

        $debug = false;

        $dataX = $param['dataTrain'];
        $testDataX = $param['dataTest'];

        $this->numPattern = count($dataX);	
        $this->numInput = count($dataX[0]);	

        $data = $this->scale($dataX, $param['min_lokal'], $param['max_lokal']);
        $_SESSION['data_prediksi_scaled'][] = $data;
        // echo '<pre>'; print_r($data); echo '</pre>';

        // echo $param['kode'].' ';
        // echo '<pre>data : '; print_r($data); 
        for($i=0;$i<$this->numPattern;$i++){
            for($j=0; $j<$this->numInput-1; $j++){
                $testData[$i][$j] = $data[$i][$j];
            }		
        }

        $dataLastTrain = $data[$this->numPattern-1];
        $idx = (count($dataLastTrain) - 1) - ($this->numInput - 2);
        for ($j= $idx; $j < $this->numInput; $j++) { 
        	$ujiData[$j] = $dataLastTrain[$j];
        }

        if ($debug) {
        	echo  "\n Proses pelatihan jaringan : ";	
        }
        

        for($e=0;$e<$numEpoch;$e++){
            $this->prosesBackpropagation($data[$e%$this->numPattern],$data[$e%$this->numPattern][$this->numInput-1]);
            $MSE = $this->mse($data[$e%$this->numPattern][$this->numInput-1]);

            if( $MSE < $treshold){
                break;
            }

            if ( $debug) {
            	if($e == 0){
	                echo "\n MSE epoch pertama: $MSE";
	            }

                if( $MSE < $treshold){
	                echo "\n Pelatihan jaringan selesai. Nilai treshold tercapai pada ".$e." iterasi.";
                	echo "\n MSE:  ".$MSE;
	            }
                
            }
        }

        

        for ($i = 0 ; $i < $this->numPattern; $i++ ){
            $this->feedForward($testData[$i]);
            $this->vectorOutput[$i] = (double)$this->Out(0);

            $out[$i] = $this->unscaleOutput($this->vectorOutput[$i], $param['min_lokal'], $param['max_lokal']);
        }
        // echo '<pre>'; print_r($this->out); 
        
        

        // echo 'test:<pre>'; print_r($testData); 
        // echo 'uji:<pre>'; print_r($ujiData); 

        $this->feedForward($ujiData);
        $hasil = (double)$this->Out(0);
        $outHasil = $this->unscaleOutput($hasil, $param['min_lokal'], $param['max_lokal']);

        if ($debug) {
        	echo "\n MSE last epoch : $MSE";

        	echo "\n Pengujian \n";	

	        echo "<br>";
	        echo "Predicted \n";
	        echo "<br>";
	        for ($i = 0 ; $i < $this->numPattern; $i++ ){
	            for($j=0; $j<$this->numInput-1; $j++){          
	                echo "  ".$testDataX[$i][$j]."  \t\t";
	            }	
	            echo "  " .abs($out[$i])."\n";echo "<br>";
	        }
	        echo "<br>";

	        echo "Prediksi data uji :  <br>";
	        for($j=0; $j<$this->numInput-1; $j++){          
	            echo "  ".$param['dataTestUji'][0][$j]."  \t\t";
	        }	
	        echo "  " .abs($outHasil)."\n";echo " --> ".(round($outHasil,0))."<br>";
	        echo "<br>";
	    }

        $param['hasil_prediksi'] = (round($outHasil,0));

        return $param;
    }

}

?>