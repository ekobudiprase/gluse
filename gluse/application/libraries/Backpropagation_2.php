<?php

define("_RAND_MAX",32767);
set_time_limit(300);
define("HI",0.9);
define("LO",0.1);

class Backpropagation {
	public $output=null;
	public $delta=null;
	public $weight=null;
	public $weightDiff=null;
	public $numLayers=null;
	public $layersSize=null;
	public $beta=null;
	public $alpha=null;
	public $prevDwt=null;
	// public $dataTrain=null;
	public $dataTest=null;
	public $NumPattern=null;
	public $NumInput=null;

    /* Minimum value in data set */
    public $minX=0;

    /* Maximum value in data set */
    public $maxX=1;

    /* Stores ann scale calculated parameters */
    public $normalizeMax=null;
    public $normalizeMin=null;

    /* Holds all output data in one array */
    public $output_vector=null;

	public $totCountBobotAllLayer = 0;
	public $debug = false;

	// public function __construct($numLayers,$layersSize,$beta,$alpha){
	public function __construct(){

	}

	public function set($numLayers,$layersSize,$beta,$alpha,$minX,$maxX){
		$this->alpha = $alpha;
		$this->beta = $beta;
        $this->minX=$minX;
        $this->maxX=$maxX;

		$this->numLayers = $numLayers;
		$this->layersSize = $layersSize;
	}

	public function createWeight(){
		$this->tot = 0;
		for($i=1; $i<$this->numLayers; $i++){
			$this->totCountBobotPerLayer[$i] = 0;
			for($j=0; $j<$this->layersSize[$i]; $j++){
				
				for($k=0; $k<$this->layersSize[$i-1]+1; $k++){
					$this->weight[$i][$j][$k] = $this->rando();
					// $this->weightDiff[$i][$j][$k] = 0 ; // assign weight diff for backward
					$this->totCountBobotAllLayer = $this->totCountBobotAllLayer + 1;
					$this->totCountBobotPerLayer[$i] = $this->totCountBobotPerLayer[$i] + 1;
				}
				
				$this->weight[$i][$j][$this->layersSize[$i-1]] = 1;
				// $this->weightDiff[$i][$j][$this->layersSize[$i-1]] = 0; // assign weight diff for backward
			}
		}

		for($i=1; $i<$this->numLayers; $i++){
			for($j=0; $j<$this->layersSize[$i]; $j++){
				for($k=0; $k<$this->layersSize[$i-1]+1; $k++){					
					$this->prevDwt[$i][$j][$k] = (double) 0.0;
				}				
			}
		}

	}

	public function rando(){
		// return (float)(rand())/(_RAND_MAX/2) - 1; //32767
		return (float)rand()/(float)getrandmax();
		// return $this->random_float(0,1);
	}

	function random_float ($min,$max) {
		return ($min+lcg_value()*(abs($max-$min)));
	}

	public function sigmoid($inputSource){
		return (double)(1.0 / (1.0 + exp(-$inputSource)));
	}

	public function mse($target){	
		$mse=0;
		
		for($i=0;$i<$this->layersSize[$this->numLayers-1];$i++){
			$mse+=($target-$this->output[$this->numLayers-1][$i])*($target-$this->output[$this->numLayers-1][$i]);		
		}	
		return $mse/2;	
	}

	public function Out($i){
		return $this->output[$this->numLayers-1][$i];
	}
	public function feedForward($inputSource){	
		$sum = 0.0;

		for($i=0;$i<$this->layersSize[0];$i++){
			$this->output[0][$i] = $inputSource[$i];
			$this->delta[0][$i] = 0; //assign delta pada tiap neuron
		}
		
	
		for($i=1;$i<$this->numLayers;$i++){	
			for($j=0;$j<$this->layersSize[$i];$j++){	
				$sum=0.0;
				for($k=0;$k<$this->layersSize[$i-1];$k++){					
	                $sum += $this->output[$i-1][$k]*$this->weight[$i][$j][$k]; //jumlah total input x bobot
	                if ($this->debug) {
	                	echo $this->output[$i-1][$k]." x ".$this->weight[$i][$j][$k]."<br>";
	            	}
				}

				$sum += $this->weight[$i][$j][$this->layersSize[$i-1]]; //jumlahkan dengan biasnya, hitung Yin
				$this->output[$i][$j] = $this->sigmoid($sum); //hitung Y
				$this->delta[$i][$j] = 0; //hitung Y
				if ($this->debug) {
                	echo "y_in = ".$sum." & y[".$i."][".$j."] = ".$this->output[$i][$j]."<br><br>";
            	}
			}
		}
		
	}

	public function backward($target){
		/* 
		hitung delta pada layer output 
		untuk delta layer lainnya nanti digenerate
		*/
		for($i=0;$i<$this->layersSize[$this->numLayers-1];$i++){	
			$this->delta[$this->numLayers-1][$i] =  ($target-$this->output[$this->numLayers-1][$i]) * $this->output[$this->numLayers-1][$i]*(1-$this->output[$this->numLayers-1][$i]);
		}
		// echo '<pre>'; print_r($this->delta); 
		// echo '<pre>'; print_r($this->weight); 

		/* 
		hitung perub. bobot pada layer terakhir
		mencari delta pada neuron layer hidden dan input
		looping mundur dari 2,1
		*/
		for($i=$this->numLayers-1;$i>0;$i--){
			/*
			loop jumlah neuron pada layer sebelum layer-i
			*/
			for($l=0;$l<$this->layersSize[$i-1];$l++){
				$deltain[$i-1][$l] = 0; // delta-in tiap neuron assign nilai 0
				// echo "[$i-1][$l] = ";
				/*
				loop jumlah neuron pada layer i
				*/
				for($m=0;$m<$this->layersSize[$i];$m++){
					// $weightT[$i-1][$l][$m] = $this->weight[$i][$m][$l];
					$weightDiff[$i-1][($this->layersSize[$i-1])][$m] = $this->alpha * $this->delta[$i][$m] ; // untuk bias
					$weightDiff[$i-1][$l][$m] = $this->alpha * $this->delta[$i][$m] * $this->output[$i-1][$m]; //cari perub bobot tiap neuron di layer i-1

					// echo "[$i-1][$l][$m] = $this->alpha  * ".$this->delta[$i][$m]." * ".$this->output[$i-1][$m];
					$deltain[$i-1][$l] += $this->weight[$i][$m][$l] * $this->delta[$i][$m]; // untuk delta-in pada layer i-1 yg nanti digunakan cari deltanya
					// echo " [$i][$m][$l]: ".$this->weight[$i][$m][$l]." * ".$this->delta[$i][$m]." + ";
					// echo "<br>";
				}

				// echo "<br>";
				$this->delta[$i-1][$l] = $deltain[$i-1][$l] * $this->output[$i-1][$l]; // nilai akhir delta tiap neuron
			}
		}
		// echo 'weightT: <pre>'; print_r($weightDiff); 

		// transform weightDiff format array like weight supaya nanti bs dicombine
		for($i=$this->numLayers-1;$i>0;$i--){
			for($j=0;$j<$this->layersSize[$i];$j++){
				for($k=0;$k<count($this->weight[$i][$j])-1;$k++){
					// $perub[$i][$j][$k] = $this->alpha * $this->delta[$i][$j] * $this->output[$i-1][$k];
					$this->weightDiff[$i][$j][$k] = $weightDiff[$i-1][$k][$j];
				}
				$this->weightDiff[$i][$j][count($this->weight[$i][$j])-1] = $this->alpha * $this->delta[$i][$j]; // punya bias tapi assign lgsg
			}
		}

		// echo '<pre>'; print_r($perub); 
		// echo 'this_weight: <pre>'; print_r($this->weight); 
		// echo 'weightT: <pre>'; print_r($weightT); 
		// echo 'this_delta: <pre>'; print_r($this->delta); 
		// echo 'this_output: <pre>'; print_r($this->output); 
		// echo 'weightDiff: <pre>'; print_r($this->weightDiff); 

	}

	public function backwardWithMomentum($target){
		/* hitung delta pada layer terakhir */
		for($i=0;$i<$this->layersSize[$this->numLayers-1];$i++){	
			$this->delta[$this->numLayers-1][$i] = ($target-$this->output[$this->numLayers-1][$i]) * $this->output[$this->numLayers-1][$i]*(1-$this->output[$this->numLayers-1][$i]);
			if ($this->debug) {
				echo "delta[".($this->numLayers-1)."][".$i."] = ".$this->delta[$this->numLayers-1][$i]."=".$this->output[$this->numLayers-1][$i]."*".(1-$this->output[$this->numLayers-1][$i])."*".($target-$this->output[$this->numLayers-1][$i]);
				echo "<br>";
			}
		}
		
		for($i=$this->numLayers-2;$i>0;$i--){ // layer antara layer ouput dan layer input
			for($j=0;$j<$this->layersSize[$i];$j++){ // sejumlah neuron pada layer ke i
				$sum=0.0;
				for($k=0;$k<$this->layersSize[$i+1];$k++){
					$sum+=$this->delta[$i+1][$k]*$this->weight[$i+1][$k][$j]; // sumproduct delta & weight scr back, hit delta-in
				}			
				$this->delta[$i][$j] = $this->output[$i][$j]*(1-$this->output[$i][$j])*$sum; //hit delta nya
			}
		}
		
		// echo "momentum: <br>";
		/*for($i=1;$i<$this->numLayers;$i++){ // 1,2
			// echo $i."per: <br>";
			for($j=0;$j<$this->layersSize[$i];$j++){ // 1:0,1,2; 2:0
				for($k=0;$k<$this->layersSize[$i-1];$k++){ //1,0,0; 1,0,1; 1,0,2; 1,1,0; ...
					$this->weight[$i][$j][$k] += $this->alpha*$this->prevDwt[$i][$j][$k]; //weight momentum per neuron
					// echo $i.', '.$j.', '.$k.'<br>';		
				}
				$this->weight[$i][$j][$this->layersSize[$i-1]]+=$this->alpha*$this->prevDwt[$i][$j][$this->layersSize[$i-1]]; //weight momentum bias
			}
			// echo "<br>";
		}*/
	}

	public function updateWeightWithMomentum(){
		for($i=1;$i<$this->numLayers;$i++){
			for($j=0;$j<$this->layersSize[$i];$j++){
				for($k=0;$k<$this->layersSize[$i-1];$k++){
					$this->prevDwt[$i][$j][$k]=$this->beta * $this->delta[$i][$j] * $this->output[$i-1][$k]; //perubahan bobot
					$this->weight[$i][$j][$k]+=$this->prevDwt[$i][$j][$k];
				}

				$this->prevDwt[$i][$j][$this->layersSize[$i-1]]=$this->beta*$this->delta[$i][$j]; //perubahan bobot pd bias
				$this->weight[$i][$j][$this->layersSize[$i-1]]+=$this->prevDwt[$i][$j][$this->layersSize[$i-1]];
			}
		}
	}

	public function updateWeight(){
		// echo 'this_weightDiff: <pre>'; print_r($this->weightDiff); 
		// echo 'this_weight: <pre>'; print_r($this->weight); 
		
		for($i=1;$i<$this->numLayers;$i++){
			for($j=0;$j<$this->layersSize[$i];$j++){
				for($k=0;$k<$this->layersSize[$i-1];$k++){
					$this->weight[$i][$j][$k]+=$this->weightDiff[$i][$j][$k];
				}

				$this->weight[$i][$j][$this->layersSize[$i-1]]+=$this->weightDiff[$i][$j][$this->layersSize[$i-1]];
			}
		}

		// echo 'this_weight_up: <pre>'; print_r($this->weight); 
	}

	public function prosesBackpropagation($inputSource, $target){	
		$this->feedForward($inputSource);
		// $this->backward($target);
		// $this->updateWeight();
		$this->backwardWithMomentum($target);
		$this->updateWeightWithMomentum();
		
	}

	public function Run($param){
		$Thresh =  0.0001;
		$epoch = 200000;	
		$MSE = 0.0;	
		$this->NumPattern = count($param['dataTrain']); //3
		$this->NumInput = count($param['dataTrain'][0]);
		
		echo '<pre>'; print_r($param['dataTrain']); 
    	$data = $this->scale($param['dataTrain']);
	    for($i=0;$i<$this->NumPattern;$i++) {
	        for($j=0;$j<$this->NumInput-1;$j++){
	            $testData[$i][$j]=$data[$i][$j];
	        }		
	    }
	    echo '<pre>'; print_r($data); 
		
		if ($this->debug) {
			echo  "Pelatihan jaringan : <br>";	
		}

		$msg_dbg = '';
		$msg_dbg .= "<br>";
		$msg_dbg .= "Kode mata kuliah : ".$param['kode']."<br>";
		$msg_dbg .= "Nama mata kuliah : ".$param['nama']."<br>";
		$msg_dbg .= "Data pelatihan : ";
		// $msg_dbg .= '<pre>'; print_r($param['dataTrain']); 

		for($e=0; $e<$epoch; $e++){	

			$this->prosesBackpropagation($data[$e%$this->NumPattern], $data[$e%$this->NumPattern][$this->NumInput-1]);
			if ($this->debug) {
				echo "Proses debugging : ";
				echo '<pre>'; print_r($param['dataTrain'][$e%$this->NumPattern]); echo '</pre>';
				echo '<pre>'; print_r($param['dataTrain'][$e%$this->NumPattern][$this->NumInput-1]); echo '</pre>';
				break;
			}

			$MSE = $this->mse($data[$e%$this->NumPattern][$this->NumInput-1]);
			if($e==0){
				$msg_dbg .= "\nMean Square Error dari epoch pertama : $MSE";
			}
			
			if( $MSE < $Thresh){
	           $msg_dbg .= "\nJaringan sudah dilatih. Nilai threshold tercapai pada ".$e." iterasi.";
	           $msg_dbg .= "\nMSE:  ".$MSE;
	           break;
	        }
		}
		
		$msg_dbg .= "\nMean Square Error dari epoch terakhir : $MSE";
		
		$msg_dbg .= "\nPrediksi pada data test : <pre>";	
		
	    for ($i = 0 ; $i < $this->NumPattern; $i++ ){
	        // $this->feedForward($param['dataTest'][$i]);
	        $this->feedForward($testData[$i]);
			$this->vectorOutput[]=(double)$this->Out(0);
	    }		

		$out = $this->unscaleOutput($this->vectorOutput);
		echo '<pre>'; print_r($this->vectorOutput); 
		echo '<pre>'; print_r($out); 

		/*$msg_dbg .= "\nPrediksi pada data test yang dicari : <pre>";	
		
	    for ($i = 0 ; $i < 1; $i++ ){
	        $this->feedForward($param['dataTestUji'][0]);
					
	        $msg_dbg .= "\n";
			
			for($j=0;$j<$this->NumInput-1;$j++){
				$msg_dbg .= $param['dataTestUji'][0][$j].",  ";
			}
						
			$msg_dbg .= (double)$this->Out(0);
			$param['hasil_prediksi'] = (double)$this->Out(0);
			$msg_dbg .= "<br>";
	    }
	    $msg_dbg .= "\n===\n";*/

	    // echo $msg_dbg;
	    
	    return $param;
		
		
	}

///////////////////////////////
/// SCALING FUNCTIONS BLOCK ///
///////////////////////////////

/* --- Set scaling parameters */
public function setScaleOutput($data){
    $oldMin = $data[0][0];
    $oldMax = $oldMin;	
    $numElem = count($data[0]);
    
    /* --- First calcualte minimum and maximum */
    for($i=0;$i<$this->NumPattern;$i++){
        $oldMin=$data[$i][0];
        $oldMax=$oldMin;	
        
        for($j=1;$j<$numElem;$j++){
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

/* --- Scale input data to range before feeding it to the network */
/*
                     x - Min
    t = (HI -LO) * (---------) + LO
                     Max-Min 
*/
public function scale($data){
    $this->setScaleOutput($data);
    $numElem = count($data[0]);
    $temp = 0.0;
    
    for($i=0; $i<$this->NumPattern; $i++){
        for($j=0;$j<$numElem;$j++){
            $temp = (HI-LO)*(($data[$i][$j] - $this->normalizeMin[$i]) / ($this->normalizeMax[$i] - $this->normalizeMin[$i])) + LO;
            $data[$i][$j]=$temp;
        }
	}
    
    return $data;
}

/* --- Unscale output data to original range */
/*
                       x - LO 
    t = (Max-Min) * (---------) + Min
                       HI-LO
*/
public function unscaleOutput($output_vector){	
    $temp=0.0;

    for( $i=0; $i < $this->NumPattern; $i++ ){
       
        $temp=($this->normalizeMax[$i]-$this->normalizeMin[$i]) * (($output_vector[$i] - LO) / (HI-LO)) + $this->normalizeMin[$i] ;	
		$unscaledVector[$i] =$temp;        
    }
    
    return $unscaledVector;
}

}
?>