// Get JSON from 'network.php'
function getNetworkDevices()
{
	var xmlhttp = new XMLHttpRequest();
	var url = "storage.php";

	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    var myArr = JSON.parse(this.responseText);
	    	document.getElementById("network").innerHTML = makeNetworkTable(myArr);
	    }
	};

	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

// Make an HTML table out of the array
function makeNetworkTable(array)
{
	// HTML table
	var table = '<table style="width:900px;text-align:left;">';
	
	// Table headers
	table += '<tr style="height:20px;">';
	table += '<th style="width:20px;">#</th>';
	table += '<th>IP Address</th>';
	table += '<th>MAC Address</th>';
	table += '<th>Vendor</th>';
	/*table += '<th>Connection</th>';
	table += '<th>Interface</th>';
	table += '<th>Class</th>';*/
	table += '<th>Hostname</th>';
	table += '<th>Last Seen</th>';
	table += '</tr>';
	
	// Prettify incomplete responses
	for(var x=0; x<array.length; x++)
	{
		if (array[x].mac == "incomplete")
		{
			array[x].mac = "(incomplete)";
			array[x].vendor = '';
			array[x].connection = '';
			array[x].class = '';
		}

	// Table content
	table += '<tr>';
	table += '<td>'+array[x].id+'</td>';
	table += '<td>'+array[x].ip+'</td>';
	table += '<td>'+array[x].mac+'</td>';
	table += '<td>'+array[x].vendor+'</td>';
	/*table += '<td>'+array[x].connection+'</td>';
	table += '<td>'+array[x].interface+'</td>';
	table += '<td>'+array[x].class+'</td>';*/
	table += '<td>'+array[x].hostname+'</td>';
	table += '<td>'+array[x].lastSeen+'</td>';
	table == '</tr>';
	}

	table += '</table>';
	return table;
}
