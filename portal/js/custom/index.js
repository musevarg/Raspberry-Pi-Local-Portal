/* IP Info and flag */

$.getJSON("https://freegeoip.app/json/", function(result){
	var regString = result.region_name == '' ? '' : result.region_name + ', ';
  	$(".ipinfo").html(result.ip + '<br>' + result.city + ', ' + regString + result.country_name);

  	var countryCode = result.region_name.toLowerCase() == 'scotland' ? 'gb-sct' : result.country_code.toLowerCase();
  	var flagClass = "flag-icon-" + countryCode;
  	$(".flag-icon").addClass(flagClass);
});

/* RSS reader */

$.getJSON('http://192.168.1.181/php/france24.php', { get_param: 'value' }, function(data) {
	var news = '';
	var france24 = [];
	var metalhammer = [];
    $.each(data.channel.item, function(index, element) {
  		france24.push('<a href="' + element.link + '" target="_blank">' + element.title + '</a>  |  ');
      });
    $.getJSON('http://192.168.1.181/php/metalhammer.php', { get_param: 'value' }, function(data) {
	    $.each(data.channel.item, function(index, element) {
  			metalhammer.push('<a href="' + element.link + '" target="_blank">' + element.title + '</a>  |  ');
  			
  			   	var smaller = france24.length < metalhammer.length ? france24.length : metalhammer.length;
			   	for(var x=0; x<smaller; x++)
			   	{
			   		news += france24[x];
			   		news += metalhammer[x];
			   	}
			    document.getElementById('france24-text').innerHTML = news;
      	});
    });
});




// Get JSON from 'network.php'
function getNetworkDevices()
{
	var xmlhttp = new XMLHttpRequest();
	var url = "http://192.168.1.181/php/networkTable.php";

	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    var myArr = JSON.parse(this.responseText);
	    	document.getElementById("networkTable").innerHTML = makeNetworkTable(myArr);
	    }
	};

	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

// Make an HTML table out of the array
function makeNetworkTable(array)
{
	// HTML table
	var table = '<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp projects-table">';
	
	// Table headers
	table += '<thead><tr>';
	table += '<th class="mdl-data-table__cell--non-numeric">#</th>';
	table += '<th class="mdl-data-table__cell--non-numeric" style="width:100px;">Name</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">IP Address</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">MAC Address</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">Vendor</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">Hostname</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">First Seen</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">Last Seen</th>';
	table += '<th class="mdl-data-table__cell--non-numeric">Connected</th>';
	table += '</tr></thead><tbody>';
	
	for(var x=0; x<array.length; x++)
	{
		// Prettify incomplete responses
		if (array[x].mac == "incomplete")
		{
			array[x].mac = "(incomplete)";
			array[x].vendor = '';
			array[x].connection = '';
			array[x].class = '';
		}

	// Table content
	table += '<tr>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyValue(array[x].id)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric" id="tdName'+array[x].id+'" onclick="toggleTextBox('+array[x].id+');">'+makeName(prettyValue(array[x].name), array[x].id)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyValue(array[x].ip)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyValue(array[x].mac)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyValue(array[x].vendor)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyValue(array[x].hostname)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyDate(array[x].firstSeen)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+prettyDate(array[x].lastSeen)+'</td>';
	table += '<td class="mdl-data-table__cell--non-numeric">'+setBadge(array[x].connected)+'</td>';
	table == '</tr>';
	}

	table += '</tbody></table>';
	return table;
}

getNetworkDevices();

function prettyDate(date){

	var d = new Date(date);
	
	var DD = String(d.getDate()).padStart(2, '0');
	var MM = String(d.getMonth() + 1).padStart(2, '0');
	var YYYY = d.getFullYear().toString().substr(-2);
	const mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d)

	var hh = d.getHours();
	if (hh<10){hh='0'+hh;}
	var mm = d.getMinutes();
	if (mm<10){mm='0'+mm;}
	var ss = d.getSeconds();
	if (ss<10){ss='0'+ss;}

	return mo + ' ' + DD + ', ' + hh + ':' + mm;
}

function prettyValue(val){
	if (val == null){return '';}
	else if (val == 'N/A'){return '';}
	else {return val;}
}

function setBadge(val) {
	if (val==1)
	{
		return '<div style="position:relative;width:50%;margin: auto;color:#00d45a;"><i class="material-icons">check_circle</i></div>';
	}
	else
	{
		return '<div style="position:relative;width:50%;margin: auto;color:#f44336;"><i class="material-icons">cancel</i></div>';
	}
}

function makeName(val, id) {
	var html = '<span id="displayName'+id+'">'+val+'</span><div style="display:none;" id="inputName'+id+'" class="mdl-textfield mdl-js-textfield getmdl-select full-size"><input class="mdl-textfield__input nameInput" type="text" id="nameValue'+id+'"></div>';
	return html;
}



/* Update network device name */

var box_id = 0;

function toggleTextBox(id){

		$("#displayName"+id).css("display", "none");
		$("#inputName"+id).css("display","block");
		$("#nameValue"+id).focus();
		box_id = id; 
		document.getElementById("tdName"+id).addEventListener("keydown", keyListener);

}



function keyListener(event){
	var id = box_id;
		var key = event.which;
		 if(key === 13)  // the enter key code
		  {
		  	var name = $("#nameValue"+id).val();
		  	$("#nameValue"+id).val('')
		  	$("#displayName"+id).text(name);
		  	$("#inputLabel"+id).text(name);	
			$("#displayName"+id).css("display", "block");
			$("#inputName"+id).css("display","none");
			document.getElementById("tdName"+id).removeEventListener("keydown", this);

			 $.ajax({url: "http://192.168.1.181/php/updateName.php?id="+id+"&name="+name, success: function(result){
			    console.log(result);
				}	
			});
		  }
		  else if (key === 27)
		  {
		  	console.log("escape key pressed");
		  	$("#displayName"+id).css("display", "block");
			$("#inputName"+id).css("display","none");
			document.getElementById("tdName"+id).removeEventListener("keydown", this);
		  }
}

function refreshTable()
{
	$("#refreshButton").css("display", "none");
	$("#loading").css("display", "block");
	$.getJSON("http://192.168.1.181/php/network.php", function(result){
		$("#refreshButton").css("display", "block");
		$("#loading").css("display", "none");
		getNetworkDevices();
	});
}


/* Load France 24 player */

function loadPlayer(){
	$("#france24player").html('<iframe class="france24" width="560" height="315" src="https://www.youtube-nocookie.com/embed/hiDW_eOhHpI?autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
}


/* Weather */

$.getJSON("http://192.168.1.181/php/weather.php", function(result){
	$("#wTemp").html(result.main.temp.toFixed(1)+'<sup>&deg;</sup>');
	$("#wText").html(result.weather[0].description);
	$("#wImg").css("backgroundImage", "url('https://openweathermap.org/img/wn/"+result.weather[0].icon+"@2x.png')");
	var img = result.weather[0].description.trim().replace(/\s+/g, '-').toLowerCase();
	$(".weather .mdl-card__supporting-text").css("backgroundImage", "url('images/weather/"+img+".jpg')")
});


/* Internet Connection Test */

function internetConnectionTest(){
	$.ajax({url: "http://192.168.1.181/php/fortune.php", success: function(result){
		$("#connectionMessage").html('<i class="material-icons" style="color:#00d45a;">wifi</i><p id="iMes">You are connected to the internet</p><p id="fortune">'+result+'</p>');
  	}, error: function(err){
  		$("#connectionMessage").html('<i class="material-icons" style="color:#f44336;">signal_wifi_off</i><p id="iMes">You are not connected to the internet</p>');
    	$("#fortune").text('');
  	}
  });
}

internetConnectionTest();



/* Date and Time */

var showSeconds = false;

var timerTime = setInterval(getDateTime ,1000);

function getDateTime()
{
	var today = new Date();

	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0');
	var yyyy = today.getFullYear().toString().substr(-2);
	const mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(today)
	var date = mo + ' ' + dd;

	var hh = today.getHours();
	if (hh<10){hh='0'+hh;}
	var mm = today.getMinutes();
	if (mm<10){mm='0'+mm;}
	var ss = today.getSeconds();
	if (ss<10){ss='0'+ss;}

	if (showSeconds)
	{
		var time = hh + ':' + mm + ':' + ss;
		var message = "Click to hide seconds";
	}
	else
	{
		var time = hh + ':' + mm;
		var message = "Click to show seconds";
	}

	document.getElementById('time').innerHTML = '<p style="position:relative;top:9px;right:15px;color:white;font-size:15px;font-weight:normal;text-align:center;"><a style="position:relative;cursor:pointer;color:white;font-size:15px;" title="' + message + '" onclick="showSeconds = showSeconds == false ? true : false;">' + time + '</a> - '+date+'</p>';
	//document.getElementById('date').innerHTML = date;
}