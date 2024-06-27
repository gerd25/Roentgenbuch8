<HTML>
    <HEAD>

     <meta charset="utf-8">

<style>

  body {
    background-color:white;

   }

  table, th, td  {border: 0px solid black; border-spacing: 0px;}
  th, td         {padding: 1px;}
  #tabelle       {background-color:#F0F0F0;}
  #spalte        {background-color: #5c82d9;}
  #zeilengruppe  {background-color: #8db243; color: #ffffff;}
  #zeile         {background-color: #e7c157;}
  #zelle         {background-color: #c32e04;}

  #DVT {
position: absolute;
  left= "40";
}
#CT {
position: absolute;
  left= "180";
}
#PZ {
position: left;

}
#VT {
position: left;

}

#Null{
     {display: none}
}
input[type=text] {
  background-color: #E0E0E0;
  color: white;
  padding: 1.5rem;
  border:0;
  height:5;
}

</style>

    </HEAD>

  <body>



<div style="position:relative; left:160px; top:1.0cm">

 <form method="post" action="http://localhost/test/Abfrage_datum_auswahl7.php" >


       <label style = "background:7FD1CE"; for="DatumVon">Datum von </label>

       <input id="DatumVon" name="datumVon" type="date" value="DatumVon">

       <label for="DatumBis">bis </label>

       <input id="DatumBis" name="datumBis" type="date" value="DatumBis">



       <INPUT id ="px" TYPE = "RADIO" name="DVT" VALUE="PX"> DVT
       <INPUT id = "OI" TYPE = "RADIO" name="DVT" VALUE="IO"> CT
       <INPUT  TYPE = "RADIO" name="DVT" VALUE="PZ"> KB
       <INPUT  TYPE = "RADIO" name="DVT" VALUE="VT"> RO
       <INPUT TYPE = "RADIO"  name="DVT" VALUE="Null"  style="display:none"  checked>

        <input  type="submit" value="Submit">


  </form>

   </div>


    <div style="position:relative; left:0px; top:1.0cm">


     <div position:absolute; left:10px; top:1px>

   <table id="tabelle" border = "0" cellspacing="0"  height= "90">
   <caption align="top">Roentgenbuch</caption>
   <tr>
    <tr></tr>
   <tr>

    <th style=' width:180 '>PatientID</th>
    <th style=' width:180 '>Patient</th>
    <th style=' width:180 '>Geburtsdatum</th>
    <th style=' width:180 '>Geschlecht</th>
    <th style=' width:180 '>Bildparameter</th>
    <th style=' width:180 '>Hersteller</th>
   </tr>


<?php


 session_start();
 //####################################################################################
//##  Zugang Datenbank   ############################
//##################################################################################

//  Zugang Datenbank

$zugang = pg_connect("host=localhost dbname=orthanc_db user=postgres password=user");
 if(!$zugang) {
      echo "Error : Unable to open database\n";
  } else {
     // echo "Opened database successfully\n";
   }
   $stat = pg_connection_status($zugang);
    if($stat === PGSQL_CONNECTION_OK){
 // echo 'Connection OK';
    } else {
        echo 'An error occurred';
    }

$result = pg_query($zugang, "SELECT * FROM datenaustausch");
       while ($row = pg_fetch_row($result)) {
     // echo "phpid: $row[0]  patid: $row[1]";
       //echo "<br />\n";
     // $PID = $row[1];
     // echo $PID;
 }
       $PID ="9706";
       //  echo $PID;
          $_SESSION["newpaID"]=$PID;

    if (!$result) {
       echo "Ein Fehler ist aufgetreten.\n";
    exit;
    }

  $eintrag = pg_query($zugang,"INSERT INTO public.datenaustausch(phpid,patid,lastname,firstname,birthday,street,city,zip,sex,confirm,commit) VALUES ('29',$PID,'oeller','Manni','03.03.2020','Weg','Herne','55555','m','t','OK')");


    if (!$eintrag) {
       echo "Ein Fehler ist aufgetreten.\n";
    exit;
    }


$result = pg_query($zugang, "UPDATE public.datenaustausch SET confirm= 'O', commit='gelesen' WHERE patid ='9706'");

   if (!$result) {
      echo "Ein Fehler ist aufgetreten.\n";
   exit;
    }




//########################################################################################
//############### Eine PatientId suchen ##################################################
//########################################################################################
// The data to send to the API



$postData = array(
    'Level' => 'patients',
    'Query' => array('PatientID' =>  $PID),
   // 'Query' => array('PatientID' =>  '798'),
    //'title' => 'A new orthanc post',
    //'content' => 'With <b>exciting</b> content...'
);


// Create the context for the request
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
         'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($postData)
        )
        ));


   // Send the request  findet patienten ID
$response = file_get_contents('http://127.0.0.1:8042/tools/find', FALSE, $context);

// Check for errors
   if($response === FALSE){
      die('Error');
   }

//  Patientendaten
// Decode the response
$responseData = json_decode($response, TRUE);

// Print the date from the response
 //echo '<pre>'; print_r($responseData); echo '</pre>';

// echo json_encode($responseData);
 $patientID = $responseData[0];
 // echo $patientID;

 //echo var_dump ($responseData);



 $curl = curl_init();

//########################################################################################
//############### Patient aus Orthanc lesen ##############################################
//########################################################################################
// Sending GET
curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/patients/$patientID");

// Telling curl to store JSON

curl_setopt($curl,
    CURLOPT_RETURNTRANSFER, true);

// Executing curl
$response = curl_exec($curl);


   if($e = curl_error($curl)) {
      echo $e;
   } else {

    // Decoding JSON data
    $decodedData =
        json_decode($response, true);
    }
//var_dump($decodedData);
       $row =  $decodedData;
    //   var_dump($row);


    // $_SESSION["newpaID"]=$PID;

   $patientGeb = $decodedData['MainDicomTags']['PatientBirthDate'];
    $_SESSION["Patgeb"]=$patientGeb;
   $PatientenID = $decodedData['MainDicomTags']['PatientID'];
    $_SESSION["PaID"]= $PatientenID;
       // echo($PatientenID);
   $Patientenname = $decodedData['MainDicomTags']['PatientName'];
    $_SESSION["PaName"]= $PatientenID;
   $PatientenWM = $decodedData['MainDicomTags']['PatientSex'];
    $_SESSION["PaSex"]= $PatientenWM;
   $Patype = $decodedData['Type'];

           ?>

            <tr>

    <!-- <td align=center border : O><?php echo $StudDate; ?></td>  !-->
    <td align=center border : O><?php echo $PatientenID; ?></td>
    <td align=center><?php echo $Patientenname; ?></td>
    <td align=center><?php echo $patientGeb; ?></td>
    <td align=center><?php echo $PatientenWM; ?></td>
    <!-- <td align=center><?php echo $descript; ?></td>
    <td align=center><?php echo $Manfac; ?></td>     !-->
   </tr>
     </table>
  </div>




   <?php
     // Studien Patient

 // $studien = $decodedData['Studies'][0];
     $studien = $decodedData['Studies'];

    //if($decodedData != Null) {
    foreach( $studien as $studs){
      curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/$studs");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // Executing curl
      $response = curl_exec($curl);
      $decodedData =
        json_decode($response, true);
     //  var_dump($decodedData);

     // Decode the response
     $responseData = json_decode($response, TRUE);

    // $AccesNr =  $decodedData['MainDicomTags']['AccessionNumber'];
     if(isset($decodedData['MainDicomTags']['AccessionNumber'])){ $AccesNr =$decodedData['MainDicomTags']['AccessionNumber']; } else
                    {$AccesNr = "keine Daten";}
     //$StudDate = $decodedData['MainDicomTags']['StudyDate'];
     if(isset($decodedData['MainDicomTags']['StudyDate'])){ $StudDate =$decodedData['MainDicomTags']['StudyDate']; } else
                    {$StudDate = "keine Daten";}
                                 $_SESSION["PaStudDate"]= $StudDate;



     $StudID =  $decodedData['MainDicomTags']['StudyID'];
     $StudyINS =  $decodedData['MainDicomTags']['StudyInstanceUID'];
     $STudyZeit= $decodedData['MainDicomTags']['StudyTime'];
     $ParentPat =  $decodedData['ParentPatient'][0];
     $Serien =  $decodedData['Series'];
     // $Serien =  $decodedData['Series'];
    // echo $Serien;
      // var_dump($Serien) ;




         foreach( $Serien as $reihen){
          curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/series/$reihen");
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($curl);
       // var_dump ($response) ;
          $decodedData = json_decode($response, true);

     // var_dump($decodedData);
         }
        $update = $decodedData['LastUpdate'];
        $bodypart = $decodedData['MainDicomTags']['BodyPartExamined'];
        $Manfac = $decodedData['MainDicomTags']['Manufacturer'];
           $_SESSION["Herstell"]= $Manfac;
        $modali = $decodedData['MainDicomTags']['Modality'];
        $date = $decodedData['MainDicomTags']['SeriesDate'];
       // $descript = $decodedData['MainDicomTags']['SeriesDescription'];
        $Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];
        $Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];
        $date = $decodedData['MainDicomTags']['SeriesDate'];
        $descript = $decodedData['MainDicomTags']['SeriesDescription'];
           $_SESSION["Beschr"]= $descript;
        $Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];
        $Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];
        $time = $decodedData['MainDicomTags']['SeriesTime'];
        $station = $decodedData['MainDicomTags']['StationName'];
        $study = $decodedData['ParentStudy'];
        $stati = $decodedData['Status'];
        $typ = $decodedData['Type'];
        $Instanzen = $decodedData['Instances'];
       //  var_dump($Instanzen);


            foreach( $Instanzen as $value){
          // var_dump($value);
              curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/tags");
             //curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$Instanzen");
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
             $response = curl_exec($curl);
             $decodedData = json_decode($response, true);



        $aquiNR =    $decodedData['0020,0012']['Value'];
        $creatdate = $decodedData['0008,0012']['Value'];
        $creattime = $decodedData['0008,0013']['Value'];
        $nummerI =   $decodedData['0020,0013']['Value'];
        $AnzahlF =   $decodedData['0028,0008']['Value'];
        $type =      $decodedData['0008,1090']['Value'];
               if(isset($decodedData['0018,0060']['Value'])){ $kvp_Wert = $decodedData['0018,0060']['Value']; } else
                    {$kvp_Wert = "keine Daten";}
               if(isset($decodedData['0018,1150']['Value'])){ $expotime = $decodedData['0018,1150']['Value']; } else
                    {$expotime = "keine Daten";}
               if(isset($decodedData['0018,1151']['Value'])){ $xrayTC = $decodedData['0018,1151']['Value'];} else
                    {$xrayTC = "keine Daten";}
        $modali =    $decodedData['0008,0060']['Value'];
        $bodypart =  $decodedData['0018,0015']['Value'];
        $station  =  $decodedData['0008,1010']['Value'];
        $PatientenWM =$decodedData['0010,0040']['Value'];

         ?>
            <div style='position:relative;top:8px;left:1px'>
        <table  id=tabelle cellspacing=0 border=1px solid align:center'  >

          <tr>
          <th><img src="http://127.0.0.1:8042/instances/<?php echo $value; ?>/preview".' alt="?" height="75" width="120" </th>

          <th style=' width:180; background:#EDEDED'>InstanceCreationDate</th>
          <th style=' width:150; background:#EDEDED'>InstanceNumber</th>
          <th style=' width:100; background:#EDEDED'>KVP</th>
          <th style='border:1; width:130; background:#EDEDED'>Exposure Time</th>
          <th style='border:1; width:160; background:#EDEDED'>xRay Tube Current</th>
          <th style='border:1; width:130; background:#EDEDED'>Modality</th>
          <th style='border:1; width:150; background:#EDEDED'>Koerperbereich</th>
          <th style='border:1; width:150; background:#EDEDED'>Indikation</th>
          <th style='border:1; width:150; background:#EDEDED'>Behandler</th>
          <th style='border:1; width:150; background:#EDEDED'>Ausfuehrender</th>
          <th style='border:1; width:150; background:#EDEDED'>Schwanger</th>
          </tr>
          <tr>
          <td height=10; style='background: #E0E0E0'; 'height:5'></td>

          <td height=10 style='background: #E0E0E0'; align='center'; 'height:5'><?php echo $creatdate; ?></td>
          <td style='background: #E0E0E0; align= center; height:5'><?php echo $nummerI; ?></td>
          <td style='background: #E0E0E0; align= center'; 'height:5'><input style="text-align:center" type ='text' name='kvp' size='10'  value= <?php echo $kvp_Wert; ?> </td>
          <td style='background: #E0E0E0; align= center'; 'height:5'><input style="text-align:center" type ='text' name='expot' size='10' value= <?php echo $expotime; ?>  style=' border:0'</td>
          <td style='background: #E0E0E0; align='center'; 'height:5'><input style="text-align:center" 'border:0' type ='text' name='xray' size='10'  value= <?php echo $xrayTC; ?> style=' border:0'</td></div>
          <td style='background: #E0E0E0; align='center'; 'height:5'><?php echo $modali; ?></td>
          <td style='background: #E0E0E0; align='center'; 'height:5'><?php echo $bodypart;?></td>
          <td style='background: #E0E0E0; align='center'; 'height:5'>   ""   </td>
          <td style='background: #E0E0E0; align='center'; 'height:5'>   ""  </td>
          <td style='background: #E0E0E0; align='center'; 'height:5'>   ""  </td>
          <td style='background: #E0E0E0; align='center'; 'height:5'>   ""  </td>
          </tr>



         <?php




                  }
                  }





    // Closing curl
    curl_close($curl);

  //######################################################################
  //#####  Auswahl
  //##############################################################
         ?>




                        </div>
         </table>

                                             <





   </body>
   </html>