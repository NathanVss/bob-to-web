$(function() {

	setInterval(Logger.getLastsLogs, 1000);



});


var Logger = 
{
	addLog : function(log)
	{
		console.log("add log");
		var html = "<div class='log'>";
		html += '<div class="log-charname f3 ">';
		html += '<p class="-nm">' + log.characterName + '</p>';
		html += '</div>';
		html += "</div>";
		$('.logs').prepend(html);
	},

	getLastsLogs : function() {
	    $.ajax({
	       url : ajaxUrlLastsLogs,
	       type : 'GET',
	       dataType : 'json',
	       success : function(json, statut){ // success est toujours en place, bien sûr !
	           console.log(json);
	           json.forEach(function(log) {
	           		console.log(log.characterName);
	           		console.log(save);
	           		Logger.addLog(log);
	           });
	       },

	       error : function(resultat, statut, erreur){

	       }

	    });

	}
	

};