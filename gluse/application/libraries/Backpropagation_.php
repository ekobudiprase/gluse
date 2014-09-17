<?php

define("_RAND_MAX",32767);

class Backpropagation {
	public $output=null;
	public $delta=null;
	public $weight=null;
	public $numLayers=null;
	public $layersSize=null;
	public $beta=null;
	public $alpha=null;
	public $prevDwt=null;
	// public $dataTrain=null;
	public $dataTest=null;
	public $NumPattern=null;
	public $NumInput=null;

	public $totCountBobotAllLayer = 0;
	public $debug = true;

	// public function __construct($numLayers,$layersSize,$beta,$alpha){
	public function __construct(){

	}

	public function set($numLayers,$layersSize,$beta,$alpha){
		$this->alpha = $alpha;
		$this->beta = $beta;

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
					$this->totCountBobotAllLayer = $this->totCountBobotAllLayer + 1;
					$this->totCountBobotPerLayer[$i] = $this->totCountBobotPerLayer[$i] + 1;
				}
				
				$this->weight[$i][$j][$this->layersSize[$i-1]] = -1;
			}
		}

		if ($this->debug) {
			echo  "Bobot : <br>";	
			echo '<pre>'; print_r($this->weight); echo '</pre>';
		}

		for($i=1; $i<$this->numLayers; $i++){
			for($j=0; $j<$this->layersSize[$i]; $j++){
				for($k=0; $k<$this->layersSize[$i-1]+1; $k++){					
					$this->prevDwt[$i][$j][$k] = (double) 0.0;
				}				
			}
		}

		if ($this->debug) {
			echo  "Bobot Prev: <br>";	
			echo '<pre>'; print_r($this->prevDwt); echo '</pre>';
		}
	}

	function printTable(){
		$table = '
			<style type="text/css">
			.myTable { background-color:#ffffff;border-collapse:collapse; }
			.myTable th { background-color:#fff; }
			.myTable td, .myTable th { padding:5px;border:1px solid #000; }
			</style>
			<table class="myTable">
		';

		$table .= '<tr><th rowspan="4">Epoch</th>';
		for ($i=1; $i <= $this->layersSize[0]; $i++) { 
			$table .= '<th rowspan="4">X'.$i.'</th>';
		}
		$table .= '<th rowspan="4">Bias</th>';
		$table .= '<th rowspan="4">Target</th>';
		$table .= '<th colspan="'.($this->totCountBobotAllLayer).'">Bobot awal</th>';
		$table .= '<th colspan="'.($this->totCountBobotAllLayer).'">y_in</th>';
		$table .= '<th rowspan="4">y</th>';
		$table .= '<th rowspan="4">delta</th>';
		$table .= '</tr>';
		$table .= '<tr>';
		$unit = '';
		$bobot_per_unit = '';

		for ($a=0; $a < 1; $a++) {		
			for ($i=0; $i < count($this->weight); $i++) { 
				$table .= '<th colspan="'.$this->totCountBobotPerLayer[$i+1].'">Layer '.($i+1==count($this->weight)?'akhir':'hidden '.($i+1)).'</th>';
				$table2 .= '<th colspan="'.$this->totCountBobotPerLayer[$i+1].'">Layer '.($i+1==count($this->weight)?'akhir':'hidden '.($i+1)).'</th>';
				for ($j=0; $j < count($this->weight[($i+1)]); $j++) { 
					$unit .= '<th colspan="'.(count($this->weight[($i+1)][$j])).'">Unit '.($j+1).'</th>';
					$unit2 .= '<th rowspan="2" colspan="'.(count($this->weight[($i+1)][$j])).'">Unit '.($j+1).'</th>';
					for ($k=0; $k < count($this->weight[($i+1)][$j]); $k++) { 
						$label = ($k+1==count($this->weight[($i+1)][$j])?'bias':($k+1));
						$bobot_per_unit .= '<th>W'.$label.'</th>';
					}
				}
			}
		}	
		$table .= $table2;
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= $unit;
		$table .= $unit2;
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= $bobot_per_unit;
		$table .= '</tr>';

		$table .= '<tr>';
		$table .= '<td>1.</td>';
		for ($i=1; $i <= $this->layersSize[0]; $i++) { 
			$table .= '<td>X'.$i.'</td>';
		}
		$table .= '<td>bias</td>';
		$table .= '<td>target</td>';

		foreach ($this->weight as $a => $itma) {
			foreach ($itma as $aa => $itmaa) {
				foreach ($itmaa as $aaa => $itmaaa) {
					$table .= '<td> '.$itmaaa.' </d>';
				}
			}
		}
		
		$table .= '<td>total</td>';
		$table .= '<td>y</td>';
		$table .= '<td>delta</td>';

		$table .= '</tr>';


		$table .= '
			</table>
		';

		// print_r($table); 
		// echo '<pre>'; print_r($this->weight); 


	}

	public function rando(){
		// echo 'rd:'.rand();
		// echo('<br>');
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
				if ($this->debug) {
                	echo "y_in = ".$sum." & y[".$i."][".$j."] = ".$this->output[$i][$j]."<br><br>";
            	}
			}
		}
		
	}

	public function prosesBackpropagation($inputSource, $target){	
		if ($this->debug) {
			echo "Feed Forward : <br>";
		}
		$this->feedForward($inputSource);
		// echo 'ini layer size<pre>'; print_r($this->layersSize); 
		
		/* hitung delta pada layer terakhir */
		for($i=0;$i<$this->layersSize[$this->numLayers-1];$i++){	
			$this->delta[$this->numLayers-1][$i] = $this->output[$this->numLayers-1][$i]*(1-$this->output[$this->numLayers-1][$i])*($target-$this->output[$this->numLayers-1][$i]);
			if ($this->debug) {
				echo "delta[".($this->numLayers-1)."][".$i."] = ".$this->delta[$this->numLayers-1][$i]."=".$this->output[$this->numLayers-1][$i]."*".(1-$this->output[$this->numLayers-1][$i])."*".($target-$this->output[$this->numLayers-1][$i]);
				echo "<br>";
			}
		}
		
		for($i=$this->numLayers-2;$i>0;$i--){ // layer antara layer ouput dan layer input
			for($j=0;$j<$this->layersSize[$i];$j++){ // sejumlah neuron pada layer ke i
				$sum=0.0;
				for($k=0;$k<$this->layersSize[$i+1];$k++){
					$sum+=$this->delta[$i+1][$k]*$this->weight[$i+1][$k][$j];
				}			
				$this->delta[$i][$j]=$this->output[$i][$j]*(1-$this->output[$i][$j])*$sum;
			}
		}
		
		echo "momentum: <br>";
		for($i=1;$i<$this->numLayers;$i++){ // 1,2
			echo $i."per: <br>";
			for($j=0;$j<$this->layersSize[$i];$j++){ // 1:0,1,2; 2:0
				for($k=0;$k<$this->layersSize[$i-1];$k++){ //1,0,0; 1,0,1; 1,0,2; 1,1,0; ...
					$this->weight[$i][$j][$k]+=$this->alpha*$this->prevDwt[$i][$j][$k]; //weight momentum per neuron
					echo $i.', '.$j.', '.$k.'<br>';		
				}
				$this->weight[$i][$j][$this->layersSize[$i-1]]+=$this->alpha*$this->prevDwt[$i][$j][$this->layersSize[$i-1]]; //weight momentum bias
			}
			echo "<br>";
		}
		
		for($i=1;$i<$this->numLayers;$i++){
			for($j=0;$j<$this->layersSize[$i];$j++){
				for($k=0;$k<$this->layersSize[$i-1];$k++){
					$this->prevDwt[$i][$j][$k]=$this->beta*$this->delta[$i][$j]*$this->output[$i-1][$k];
					$this->weight[$i][$j][$k]+=$this->prevDwt[$i][$j][$k];
				}

				$this->prevDwt[$i][$j][$this->layersSize[$i-1]]=$this->beta*$this->delta[$i][$j];
				$this->weight[$i][$j][$this->layersSize[$i-1]]+=$this->prevDwt[$i][$j][$this->layersSize[$i-1]];
			}
		}
	}

	public function Run($param){
		$Thresh =  0.0001;
		$epoch = 200000;	
		$MSE = 0.0;	
		$NumPattern = count($param['dataTrain']);
		$NumInput = count($param['dataTrain'][0]);
		
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
			

			$this->prosesBackpropagation($param['dataTrain'][$e%$NumPattern], $param['dataTrain'][$e%$NumPattern][$NumInput-1]);
			if ($this->debug) {
				echo "Proses debugging : ";
				echo '<pre>'; print_r($param['dataTrain'][$e%$NumPattern]); echo '</pre>';
				echo '<pre>'; print_r($param['dataTrain'][$e%$NumPattern][$NumInput-1]); echo '</pre>';
				break;
			}
			$MSE = $this->mse($param['dataTrain'][$e%$NumPattern][$NumInput-1]);
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
		
	    for ($i = 0 ; $i < $NumPattern; $i++ ){
	        $this->feedForward($param['dataTest'][$i]);
					
	        $msg_dbg .= "\n";
			
			for($j=0;$j<$NumInput-1;$j++){
				$msg_dbg .= $param['dataTest'][$i][$j].",  ";
			}
						
			$msg_dbg .= (double)$this->Out(0);
			$msg_dbg .= "<br>";
	    }		
		$msg_dbg .= "\n===\n";
		
		$msg_dbg .= "\nPrediksi pada data test yang dicari : <pre>";	
		
	    for ($i = 0 ; $i < 1; $i++ ){
	        $this->feedForward($param['dataTestUji'][0]);
					
	        $msg_dbg .= "\n";
			
			for($j=0;$j<$NumInput-1;$j++){
				$msg_dbg .= $param['dataTestUji'][0][$j].",  ";
			}
						
			$msg_dbg .= (double)$this->Out(0);
			$param['hasil_prediksi'] = (double)$this->Out(0);
			$msg_dbg .= "<br>";
	    }
	    $msg_dbg .= "\n===\n";

	    echo $msg_dbg;
	    
	    return $param;
		
		
	}

}
?>