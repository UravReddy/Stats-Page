<!DOCTYPE html>
<script>
<!--

//enter refresh time in "minutes:seconds" Minutes should range from 0 to inifinity. Seconds should range from 0 to 59
var limit="0:25"

var doctitle = document.title
var parselimit=limit.split(":")
parselimit=parselimit[0]*60+parselimit[1]*1

function beginrefresh(){
	if (parselimit==1)
		window.location.reload()
	else{ 
		parselimit-=1
		curmin=Math.floor(parselimit/60)
		cursec=parselimit%60
		if (curmin!=0)
			curtime=curmin+" minutes and "+cursec+" seconds left until page refresh!"
		else
			curtime=cursec+" seconds left until page refresh!"
		document.title = doctitle + ' (' + curtime +')'
		setTimeout("beginrefresh()",1000)
	}
}

if (window.addEventListener)
	window.addEventListener("load", beginrefresh, false)
else if (window.attachEvent)
	window.attachEvent("load", beginrefresh)

//-->
</script>
 
<?php
# Log the user ip address into a txt file
$file = "./incontractip.txt";
$date   = date("Y-m-d H:i:s");
$ip     = $_SERVER["REMOTE_ADDR"];  
$write =  "Date = ".$date." :>IP = ".$ip."|"."\n";
file_put_contents($file, $write, FILE_APPEND);

?>


<?php
	
	$servername = "server address";
	$username = "username";
	$password = "password";
	$dbname = "dbname";	
	


	$conn = new mysqli($servername,$username,$password,$dbname);




	if ($conn->connect_error)	{
		die("Connection failed : " . $conn->connect_error);
					}	


#Table name is cellcstats
	$sql = "SELECT * FROM cellcstats  ORDER BY Sales+0 DESC";

	$result = $conn->query($sql);
	$query = mysqli_query($conn, $sql);
	?>
<html>
<head>

	<title> Sales Dashboard</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.js"></script>
<script type="text/javascript">

# This funciton enables a table cell to turn red when an agent is on a paused status
# or when their stats such as senback ratio is outside the safe zone, else highlight green 
$(document).ready(function(){
    $('.data-table td.y_n').each(function(){
        if ($(this).text() == 'PAUSED') {
            $(this).css('background-color','#f00');
        }

	         if ($(this).text() == '') {
            $(this).css('background-color','#000000');
        }
    });

	$('.data-table td.s_p').each(function(){
        if ($(this).text() > 5.0) {
            $(this).css('background-color','#f00');
        }

	         if ($(this).text() < 5.0) {
            $(this).css('background-color','#2AFC00');
        }
    });

	
	$('.data-table td.neg_val').each(function(){
        if ($(this).text() < 0 ) {
            $(this).css('background-color','#25FC1B');
        }

	         if ($(this).text() >= 1) {
            $(this).css('background-color','#f00');
        }
    });
	
});

</script>

	<style type="text/css">
html {
  zoom:75%;
}

tr:nth-child(even) {background-color: #f4fbff;}
tr:nth-child(odd) {background-color: #96D2F6;}

td[value="PAUSED"] {
    background-color: #ffd164;
}

th {
    background-color: #4CAF50;
    color: white;
}
		body{ 
			font-size: 20px;
			color : #343d44;
			font-family: "segoe-ui:, "open-sans", tahoma, arial;
			padding : 10;
			margin: 0;
		     }
		table {
			margin : auto;
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
			font-size : 16px;
{
	margin : 25px auto 0;
	text-align: center;
	text-transform: uppercase;
	font-size : 20px;
}

table td {
	transition : all .5s;
	}
	/* Table */
	.data-table {
		border-collapse: collapse;
		font-size: 20px;
		min-width: 537px;
		    }
	.data-table th,
	.data-table td {
				border: 1px solid #e1edff;
				padding: 7px 17px;
			}
	.data-table caption {
				margin:7px;
			    }

	/* Table Header*/
	.data-table thead th {
				bgcolor="#508abb";
				color: #0BE6FE;
				border-color: #6ea1cc !important;
				text-transform: uppercase;			
			      }
	/* Table Body */
	.data-table tbody td {
			background: yellow;
			     }	
	.data-table tbody td:first-child,
	.data-table tbody td:nth-child(even){background:red},
	.data-table tbody td:last-child {
						text-align: right;
					}
	.data-table tbody tr:nth-child(odd) { 
			 bgcolor="#0BE6FE";
			
						}
	.data-table tbody tr:hover td { 
				bgcolor="#0BE6FE";
				border-color: #ffff0f;	
				      }
	/* Table Footer */
	.data-table tfoot th {
			background-color: #e5f5ff;
			text-align: right;
			     }
	.data-table tfoot th:first-child {
				text-align: left;
					}
	.data-table tbody td:empty
				{
				bgcolor: #0BE6FE;
				}
	</style>
		
</head>
<body>
		<table class="data-table"; style="float: center;">
		<caption class="title">EC3 CELLC DAILY USER STATS</caption>
		<thead>
			<tr >
				<th>AGENT</th>
				<th>CALLS</th>
				<th>SALES</th>
				<th>QA-SALE</th>				
				<th>SB</th>
				<th>SB% </th>
				<th>TIME</th>
				<th>PAUSE</th>

				<th>MTD</th>				
				<th>QA-MTD</th>	
				<th>T</th>
				<th>MTD COMM</th>
				<th>CONVERSION</th>
				<th>TALK TIME</TH>
				
				
			</tr>			
		</thead>
               <tbody>
                       <?php
                               $count=0;
                               $no =1;
                               $total=0;
                               $sumsales =0;
                               $summtd = 0;
			while ($row= mysqli_fetch_array($query)) {		
				$person=$row['AgentName'];
				$ttarget=120-$row['MTDSales'];
				$summtd=$summtd+$row['MTDSales'];
				$sumsales=$sumsales+$row['Sales'];
				$talkconvert= gmdate("H:i:s", $row['TalkTime']);
				$person2=$person.'%';
				$comm=$row['comm'];
				$mtdcomm=$row['commmtd'];
				$qadaily=$row['qadaily'];
				$qamtd=$row['qamtd'];
				$xmas=$row['xmascomm'];
				$Conversion = round($row['qamtd']/$row['leads']*100,2);
				$sbpercent = round($row['Sendback']/$row['MTDSales']*100,2);
				
			# This block highlights the top 3 performers in gold	
			if ($count <= 2){
		echo'<tr bgcolor="#f69dea">
				<td bgcolor="#FFD700">'.$person.'</td>
				<td >'.$row['Calls'].'</td>
				<td ><b>'.$row['Sales'].'</b></td>
				<td ><b>'.$qadaily.'</b></td>
				<td ><b>'.$row['Sendback'].'</b></td>	
				<td class="s_p"><b>'.$sbpercent.'</b></td>
				<td >'.$row['PauseTime'].'</td>
				<td class="y_n"><b>'.$row['PauseStatus'].'</b></td>				
				<td ><b>'.$row['MTDSales'].'</b></td>
				<td ><b>'.$qamtd.'</b></td>
				<td class ="neg_val"><b> '.$ttarget.' </b></td>
				<td>R'.$mtdcomm.'</td>	
				<td><b>'.$Conversion.'%</b></td>
				<td >'.$talkconvert.'</td>				
							
				</tr>';
				$count=$count+1;	    			    
			}else{	
		echo'<tr bgcolor="#f69dea">
				<td>'.$person.'</td>
				<td>'.$row['Calls'].'</td>
				<td><b>'.$row['Sales'].'</b></td>
				<td ><b>'.$qadaily.'</b></td>
				<td><b>'.$row['Sendback'].'</b></td>	
				<td class="s_p"><b>'.$sbpercent.'</b></td>
				<td>'.$row['PauseTime'].'</td>
				<td class="y_n"><b>'.$row['PauseStatus'].'</b></td>
				<td><b>'.$row['MTDSales'].'</b></td>
				<td ><b>'.$qamtd.'</b></td>
				<td class ="neg_val"><b> '.$ttarget.' </b></td>
				<td>R'.$mtdcomm.'</td>
				<td><b>'.$Conversion.'%</b></td>
				<td>'.$talkconvert.'</td>	
				
				</tr>';
				$count=$count+1;		
		       	    					     } }                                              
echo' <tr>
 				<td><b>TOTAL</b></td>
				<td></td>
				<td><b>'.$sumsales.'</b></td>
				<td><b></b></td>
				<td><b></b></td>	
				<td ><b></b></td>
				<td ><b></b></td>
				<td></td>
				<td ><b></b></td>
				<td><b>'.$summtd.'</b></td>
				<td></td>			
				<td></td>			
				<td ><b></b></td>	
				<td></td>
				
				<td ></td>	
</tr>';

echo '
<tr >


</tr>';

	
#$sql2 = "SELECT * FROM vodacom ORDER BY sales+0 DESC";
#$result2 = $conn->query($sql2);
#$query2 = mysqli_query($conn, $sql2);
#while ($row2= mysqli_fetch_array($query2))
# {
#	$ttarget=70-$row2['mtdsales'];
	#$talkconvert= gmdate("H:i:s", $row2['talktime']); 
	#echo'<tr bgcolor="#f69dea">
	#<td>'.$row2['agent'].'</td>
	#<td>'.$row2['calls'].'</td>
	#<td><b>'.$row2['sales'].'</b></td>
	#<td><b></b></td>	
	#<td class="y_n"><b>'.$row2['paused'].'</b></td>
	#<td>'.$row2['pausetime'].'</td>
	#<td class ="neg_val"><b>'.$ttarget.'</b></td>
	#<td><b>'.$row2['mtdsales'].'</b></td>
	#<td></td>
	#<td>'.$talkconvert.'</td>									
	#</tr>';
 #}
 $conn->close(); 
 $conn2->close();	
 ?>
               </tbody>
		</table>
</body>
</html>


