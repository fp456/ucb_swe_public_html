<?php
function makeCSV($event){
  $table = "outreachEvents";
  $outfile="../CSV/" . $event . ".csv";
  $of=fopen($outfile, "w+");

  $header="TIMESTAMP,PARENT,CHILD,PRIMARY PHONE,SECONDARY PHONE,EMAIL,SCHOOL,GRADE,DEPOSIT,REFERENCE,EVENT,CODE\n";
  fwrite($of, $header);

  $data = mysql_query("SELECT * FROM $table WHERE event='$event'");
  $num_rows = mysql_num_rows($data);
  $num_fields = mysql_num_fields($data);
  for ($row_counter = 0; $row_counter < $num_rows; $row_counter += 1){
    for ($field_counter = 0; $field_counter < $num_fields; $field_counter += 1){
      $d=mysql_result($data, $row_counter, $field_counter);
      if ($field_counter != 0)
	fwrite($of, ",");
      fwrite($of, '"' . $d . '"');
    }
    fwrite($of, "\n");
  }
  fclose($of);
  chmod($outfile, 0644);
}
?>