<?php

/**
 * This function updates a file with format:
 * type=>counter
 * 
 * @param string $file path to file with that format.
 * @param array $data array with format data['type']=counter_value
 * @return int -1 if problems;  
 */

function update_counter_file($file,$data){
  global $EQUAL;
  
  if(!($fd=fopen($file,"w"))) return(-1);
  foreach($data as $key => $value) 
    fputs($fd,$key . $EQUAL . $value . "\n\r"); //Actualizamos fichero
  fclose($fd);
}


/**
 * Increases counter file
 * 
 * File has line format: tipo => integer
 * There is a special line, which is always incremented
 * and contains the sum of all counters.
 * @param string $file path to counter file
 * @param string $tipo identifier, usually will be a gid or phid. 
 * @return boolean true if ok, false if not
 */

function add_listed_visit($file, $tipo) {
  global $EQUAL,$TOTAL_LIST;

  //echo "add_listed_visit($file, $tipo)";
     
     $visits=get_all_data($file);
     $visits[$TOTAL_LIST]++;
     $visits[$tipo]++;
     if (!update_counter_file($file,$visits)) return(false);
     //echo "<pre>visits: \n";print_r($visits);echo "</pre>";
     return(true);
 }

/**
 * Decreases a counter identified by type in the file an amount.
 * @param string $file path to file
 * @param string $type counter identifier (gid or phid in general)
 * @param interger $amount how many units it is decremented;
 */

function decrease_counter($file, $type, $amount=1) {
  global $TOTAL_LIST;
 
  //echo "decrease_counter($file, $tipo)";
     
     $visits=get_all_data($file);
     $visits[$TOTAL_LIST]-=$amount;
     $visits[$type]-=$amount;
     //echo "<pre>visits: \n";print_r($visits);echo "</pre>";
     if (!update_counter_file($file,$visits)) return(false);
     
     return(true);

}

?>
