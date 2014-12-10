$(function() {
	StatusManager.getStatus();
	setInterval(StatusManager.getStatus, 4000);
	// Logger.getLastsLogs();


});


var StatusManager = 
{
	inited : false,

	deleteStatus: function() {
		$('.status-container .status').remove();
	},
	
	addStatus: function(status) {

		var html = '<div class="status f2">';
		html += '<div class="status-name">';
		html += '<p>' + status.name + '</p>';
		html += '</div>';


		html += '<div class="status-hp">';
		html += '<p>' + status.hp + '% HP</p>';
		html += '</div>';		

		html += '<div class="status-exp">';
		html += '<p>' + status.hp + '% EXP</p>';
		html += '</div>';

		if(status.isInGame == 'Yes') {
			html += '<div class="status-is-in-game status-cool">';
		} else {
			html += '<div class="status-is-in-game status-bad">';
		}
		
		html += '<p>Is in Game : ' + status.isInGame + '</p>';
		html += '</div>';

		if(status.status == 'Leveling') {
			html += '<div class="status-status status-cool">';
		} else {
			html += '<div class="status-status status-bad">';
		}
		
		html += '<p>' + status.status + '</p>';
		html += '</div>';	

		html += '</div>';

		$('.status-container').append(html);	
	},

	getStatus : function() {
	    $.ajax({
	       url : ajaxUrlStatus,
	       type : 'GET',
	       dataType : 'json',
	       success : function(json, statut){ // success est toujours en place, bien sûr !
				console.log(json);
				// console.log(Logger.inited);
				var i = 0;
				StatusManager.deleteStatus();
				for(status in json) {
					var curStatus = json[status];
					StatusManager.addStatus(curStatus);
				}
	           
	       },

	       error : function(resultat, statut, erreur){

	       }

	    });

	}
	

};