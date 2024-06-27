<html>

<head>
    <title>Hello!</title>
    <style>
  body {
	background-color:white ;
   }
  table, th, td  {border: 0px solid black; border-spacing: 1px;}
  th, td         {padding: 1px;}
  #tabelle       {background-color: #F0F0F0;}

  input[type=text] {
  background-color: #E0E0E0;
  color: white;
  padding: 1.5rem;
  border:0;
  height:5;
}

</style>

</head>

<body>


  <input type="button" value="Zurueck" onClick="javascript:history.back()">

 <table>
     <div style="position:absolute; left:50px; top:3cm">
  <table id="tabelle" border = 4 cellspacing="0"  height= "90">

  <caption align="top">Roentgenbuch</caption>


   <tr>
    <div style="position:absolute; left:50px; top:3cm">


    <th style='border:0; width:180'>Aufnahme Datum</th>
    <th style='border:0; width:180'>PatientID</th>
    <th style='border:0; width:180'>Patient</th>
    <th style='border:0; width:180'>Geburtsdatum</th>
    <th style='border:0; width:180'>Geschlecht</th>
    <th style='border:0; width:180'>Bildparameter</th>
    <th style='border:0; width:180'>Hersteller</th>


   </tr>


 <?php



    session_start();

    //###########  DAten für Tabelle
      // $peng =  $_SESSION["newpaID"];
          $patientGeb  = $_SESSION["Patgeb"];
          $PatientenID = $_SESSION["PaID"];
          $Patientenname = $_SESSION["PaName"];
          $PatientenWM = $_SESSION["PaSex"];
          $StudDate = $_SESSION["PaStudDate"];
          $Manfac = $_SESSION["Herstell"];
          $descript              = $_SESSION["Beschr"] ;
          //echo $Manfac;
      ?>
      <tr>

     <td align = center> <?php  echo $StudDate; ?> </td>
     <td align = center> <?php echo $PatientenID; ?></td>
     <td align = center> <?php echo $Patientenname; ?></td>
     <td align = center> <?php echo $patientGeb; ?></td>
     <td align = center> <?php echo $PatientenWM; ?></td>
     <td align = center> <?php echo $descript; ?></td>
     <td align=center><?php echo $Manfac; ?></td>

     </tr>
     </table>
     </div>
     </body>
     </html>


  <?php
    //##################  Tabelle Ende

       //  echo  $_SESSION["newpaID"];
          $peng =  $_SESSION["newpaID"];
       // $peng = "9706";
         //  echo $peng;
          //$modalities2 = "IO";
 // Datum Übergabe
           // $modalities2 = "IO";
         // var_dump($modalities2);


    //If($datumVon != Null) { $datumVon=$_POST["datumVon"];
 // echo $datumVon;
        $datumVon = $_POST["datumVon"];
        $jetzt = $datumVon;
        $search=   '-' ;
        $replace = '' ;
        $string= str_replace( $search,$replace,$jetzt) ;
     // echo $string;
   // } else { $datumVon = 'keine Daten';}


 //   If($datumBis != Null){$datumBis=$_POST["datumBis"] ;
        $datumBis=$_POST["datumBis"] ;
        $jetzt2 = $datumBis;
        $search=   '-' ;
        $replace = '' ;
        $string2= str_replace( $search,$replace,$jetzt2) ;
     // echo $string2;
   //  } else { $datumBis = 'keine Daten';}


              $modalities2 = $_POST["DVT"];
             // echo $modalities2;
               $null = "Null";

   // $modalities2 = "IO";
         //echo($modalities2);



$curl = curl_init();

// Sending GET


// Telling curl to store JSON
//#############################################


// The data to send to the API
$postData = array(
            "Level" => "Study",
            "Query" => array( "PatientID" => "$peng","StudyDate" => "$string-$string2"),

   //  "Query" => array( "PatientID" => "$_SESSION[newpaID]","StudyDate" => "$string-$string2"),
  //  "Query" => array( "StudyDate" => "$string-$string2"),
     // 'Query' => array('PatientID' => '3883'),
    //'title' => 'A new orthanc post',
    //'content' => 'With <b>exciting</b> content...'
             );

    //var_dump($postData);


// Create the context for the request
  $context = stream_context_create(array(
            'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($postData)
             )
             ));

// Send the request

$response = file_get_contents('http://127.0.0.1:8042/tools/find', FALSE, $context);

// Check for errors
       if($response === FALSE){
            die('Error');
        echo "keine Daten";
        }

// Decode the response

 //#########################
$responseData = json_decode($response, TRUE);


//var_dump($responseData) ;

// Print the date from the response
 //echo '<pre>'; print_r($responseData); echo '</pre>';

 //echo json_encode($responseData);



 //echo var_dump ($responseData);

 //geaendert
//If($responseData != Null) {



//#########################################
  $studydatum  = $responseData;




// } else { $studydatum = 'keine Daten';}
// var_dump ($studydatum) ;
 //echo  $responseData;


    foreach($studydatum as $studie){
    //echo $studie;

      curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/$studie");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // Executing curl
      $response = curl_exec($curl);
      $decodedData =
            json_decode($response, true);

        $DatenDatum = $decodedData['ID'];
        //var_dump($DatenDatum);
     // var_dump($decodedData);
       // echo $decodedData;
      //######################################################
     //####################################  Versuch daten zu exportieren  #############################
     //########################################################################################################


    // curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/5e3537e9-ca49c553-a7a32eae-58d55de9-00bf0c28/archive");
     curl_setopt($curl, CURLOPT_URL,"http://127.0.0.1:8042/studies/$DatenDatum/archive");
     //curl "http://127.0.0.1:8042/studies/$DatenDatum/archive";
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     $daten = curl_exec($curl);
    //$bild= curl_exec($curl);
   // var_dump($daten);

      //  $bild($bild);
      //versuch daten zu expotieren Ende  #############################
      //##################################################################################################################
     //##################################################################################################

     //####################################################################################################
     //############################# PDF drucken, nur moeglich wenn TAG 0042,0011 vorhanden ist ###############
     //#########################################################################################
     //    curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/1915e0cc-c2c1a0fc-12cdd7f5-3ba32114-a97c2c9b/content/0042,0011");
     //##########################################################################################################
     //##########################################################################################################



     //if($decodedData != Null) {

                  // $decodedData['PatientMainDicomTags']['PatientID'];
         // if(isset($decodedData['MainDicomTags']['AccessionNumber'])){ $AccesNr = $decodedData['MainDicomTags']['AccessionNumber']; } else
                  // {$AccesNr = "keine Daten";}
          //echo  $AccesNr;
          if(isset($decodedData['MainDicomTags']['StudyDate'])){ $StudDate = $decodedData['MainDicomTags']['StudyDate']; } else
                   {$StudDate = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyID'])){ $StudID = $decodedData['MainDicomTags']['StudyID']; } else
                   {$StudID = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyInstanceUID'])){ $StudyINS = $decodedData['MainDicomTags']['StudyInstanceUID']; } else
                   {$StudyINS = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyTime'])){ $STudyZeit = $decodedData['MainDicomTags']['StudyTime']; } else
                   {$STudyZeit = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientID'])){ $PatientenID = $decodedData['PatientMainDicomTags']['PatientID']; } else
                  {$PatientenID = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientName'])){ $Patientenname = $decodedData['PatientMainDicomTags']['PatientName']; } else
                  {$Patientenname = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientBirthDate'])){ $patientGeb= $decodedData['PatientMainDicomTags']['PatientBirthDate'];} else
                  {$patientGeb = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientSex'])){$PatientenWM= $decodedData['PatientMainDicomTags']['PatientSex'];} else
                  {$PatientenWM = "keine Daten";}
          //$Patype= $decodedData['PatientMainDicomTags']['Type'];
           //####### $ParentPat =  $decodedData['ParentPatient'][0];
           // $Serien =  $decodedData['Series'][0];
          $Serien =  $decodedData['Series'];
               //echo $Serien;



  //###################################################################
  //##################auswahl Daten
  //################################### ###################################################################


        foreach( $Serien as $reihen){
           curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/series/$reihen");
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           $response = curl_exec($curl);

           $decodedData = json_decode($response, true);
              // var_dump($decodedData);

            $update = $decodedData['LastUpdate'];

           if(isset($decodedData['MainDicomTags']['BodyPartExamined'])){$bodypart = $decodedData['MainDicomTags']['BodyPartExamined']; } else
                    {$bodypart = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['Manufacturer'])){$Manfac = $decodedData['MainDicomTags']['Manufacturer']; } else
                    {$Manfac = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['Modality'])){$modali = $decodedData['MainDicomTags']['Modality'];} else
                    {$modali = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDate'])){$date = $decodedData['MainDicomTags']['SeriesDate']; } else
                    {$date = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDescription'])){$descript = $decodedData['MainDicomTags']['SeriesDescription'];} else
                    {$descript = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesInstanceUID'])){$Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];} else
                    {$Instance = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesNumber'])){$Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];} else
                    {$Nummer = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDate'])){$date = $decodedData['MainDicomTags']['SeriesDate'];} else
                    {$date = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDescription'])){$descript = $decodedData['MainDicomTags']['SeriesDescription'];} else
                    {$$descript = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesInstanceUID'])){$Instance = $decodedData['MainDicomTags']['SeriesInstanceUID']; } else
                    {$Instance = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesNumber'])){$Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];}  else
                    {$Nummer = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesTime'])){$time = $decodedData['MainDicomTags']['SeriesTime'];} else
                    {$time = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['StationName'])){ $station = $decodedData['MainDicomTags']['StationName'];} else
                    {$station = "keine Daten";}
           if(isset($decodedData['ParentStudy'])){$study = $decodedData['ParentStudy']; } else
                    {$study = "keine Daten";}


  //#############################################################################################################
  //#######################                    Abfrage modalies
  //#######################################################################################################


               // var_dump($modali);
                //  echo $modalities2;



               //$study = $decodedData['ParentStudy'];
                     $stati = $decodedData['Status'];
                     $typ = $decodedData['Type'];
                    if($decodedData != Null) {
                          $Instanzen = $decodedData['Instances'];} else {die ( "keine Daten");}




                       If($modali == $modalities2 and $PatientenID == $peng )  {

                             if(isset($decodedData['Instances'])){ $Instanzen = $decodedData['Instances']; }
                                       else {$Instanzen = "keine Daten";}
                                      $Instanzen = $decodedData['Instances'];
                                        //var_dump($Instanzen);






       // $Instanzen = $decodedData['Instances'];
       // var_dump ($Instanzen);
     foreach( $Instanzen as $value){

         curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/tags");

         //curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$Instanzen");
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($curl);
          $decodedData = json_decode($response, true);
       //   var_dump($decodedData);

          curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/preview");
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $responsen = curl_exec($curl);
          var_dump($responsen);


          curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/file");
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $responsenn = curl_exec($curl);
          //curl_exec($curl);
          var_dump($responsenn);



          // $fileG = $decodedData['FileSize'];
          //$fileU = $decodedData['FileUuid'];
          //$IDnr = $decodedData['ID'];
          //$IDIserie =  $decodedData['IndexInSeries'];
          // if( $PAIDent =  $decodedData['0010,0020']['Value'] == $patientID)//{
         if(isset($decodedData['0020,0012']['Value'])){$aquiNR = $decodedData['0020,0012']['Value'];} else
            {$aquiNR = "keine Daten";}
         if(isset($decodedData['0008,0012']['Value'])){$creatdate = $decodedData['0008,0012']['Value'];} else
            {$creatdate = "keine Daten";}
         if(isset($decodedData['0008,0013']['Value'])){$creattime = $decodedData['0008,0013']['Value']; } else
            {$creattime = "keine Daten";}

         if(isset($decodedData['0020,0013']['Value'])){$nummerI = $decodedData['0020,0013']['Value'];} else
            {$nummerI = "keine Daten";}
         if(isset($decodedData['0028,0008']['Value'])){$AnzahlF =   $decodedData['0028,0008']['Value'];} else
            {$AnzahlF = "keine Daten";}
         if(isset($decodedData['0008,1090']['Value'])){$type = $decodedData['0008,1090']['Value']; } else
            {$type = "keine Daten";}
            //  $kvp_Wert =  $decodedData['0018,0060']['Value'];
         if(isset($decodedData['0018,0060']['Value'])){ $kvp_Wert = $decodedData['0018,0060']['Value']; } else
            {$kvp_Wert = "keine Daten";}
         if(isset($decodedData['0018,1150']['Value'])){ $expotime = $decodedData['0018,1150']['Value']; } else
            {$expotime = "keine Daten";}
         if(isset($decodedData['0018,1151']['Value'])){ $xrayTC = $decodedData['0018,1151']['Value'];} else
            {$xrayTC = "keine Daten";}

        echo "<div style='position:relative;top:0cm;left:10px'>\n"  ;
        echo "<table style=' id:tabelle; cellspacing:0; border:1px solid; align:center;'>\n";
        echo "<tr>\n";

         //( http://127.0.0.1:8042/instances/dce124bb-4de12a17-d3da45cd-7cf4c0d4-ae6574b5/preview
      //  echo "<th> ".'<img src='."http://127.0.0.1:8042/instances/2b9459cf-60f068a3-f285146f-ca4db519-98757af0/preview".' alt="?" height="75" width="120"/>'."</th>";
         echo "<th> ".'<img src='."http://127.0.0.1:8042/instances/$value/preview".' alt="?" height="75" width="120"/>'."</th>";




        echo "<th style='border:0; width:170px; background:#EDEDED'>AcquisitionNumber</th>\n";
        echo "<th style='border:0; width:160px; background:#EDEDED'>InstanceCreationDate</th>\n";
        echo "<th style='border:1; width:100px; background:#EDEDED'>InstanceNumber</th>\n";
        echo "<th style='border:1; width:70px;  background:#EDEDED'>KVP</th>\n";
        echo "<th style='border:1; width:130px; background:#EDEDED'>Exposure Time</th>\n";
        echo "<th style='border:1; width:150px; background:#EDEDED'>xRay Tube Current</th>\n";
        echo "<th style= 'width:70px; background:#EDEDED'>Modality</th>\n";
        echo "<th style= 'width:150px; background:#EDEDED'>Koerperbereich</th>\n";
        echo "<th style='border:1; width:150; background:#EDEDED'>Indikation</th>\n";
        echo "<th style='border:1; width:150; background:#EDEDED'>Behandler</th>\n";
        echo "<th style='border:1; width:150; background:#EDEDED'>Ausfuehrender</th>\n";
        echo "<th style='border:1; width:150; background:#EDEDED'>Schwanger</th>\n";

        echo "</tr>\n";

           echo "<tr style='align:center'>\n";
            // echo "<td> ".'<img src='."http://127.0.0.1:8042/instances/2b9459cf-60f068a3-f285146f-ca4db519-98757af0/preview".' alt="?" height="100" width="120"/>'."</td>";


             echo "<td style='border:0; align:center; background: #E0E0E0'></td>\n";
             echo "<td Style='align:center; background: #E0E0E0'> $aquiNR</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> $creatdate</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> $nummerI</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> $kvp_Wert</td>";
             echo "<td style = 'align:center; background: #E0E0E0'> $expotime</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> $xrayTC</td>";
             echo "<td style='align:center; background: #E0E0E0'> $modali</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> $bodypart</td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'> <input style='text-align:center' type ='text' name='Indikation' size='10'  value= $bodypart style=' border:0'> </td>\n";
             echo "<td style= 'align:center; background: #E0E0E0'><input style='text-align:center' type ='text' name='Behandler' size='10'  value= $bodypart style=' border:0'>   </td>\n";
             echo "<td style='align:center; background: #E0E0E0'><input style='text-align:center' type ='text' name='Ausfuehrender' size='10'  value= $bodypart style=' border:0'> </td>\n";
             echo "<td style='align:center; background: #E0E0E0'><inputstyle='text-align:center' type ='text' name='schwanger' size='10'  value= $bodypart style=' border:0' > </td>\n";


          echo "</tr>\n";

        echo "</table>\n";
        echo "</div>";

              // }
         }
            } else {"keine Daten";}


        // } else { echo "keine Daten";}






            //curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:8042/instances/$value/preview");
           // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           // $responsen = curl_exec($curl);
          // $bildData = json_decode($responsen, true);
          //  var_dump ($value);
         //    echo  ($bildData);






        }






          }


  // Closing curl
curl_close($curl);


?>
