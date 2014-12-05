$(function() {

	setInterval(Logger.getLastsLogs, 1000);
	// Logger.getLastsLogs();


});


var Logger = 
{
	inited : false,
	addLog : function(log, params)
	{
		var existingLogs = $('.logs .log');
		//console.log(existingLogs);
		var alreadyExists = false;
		existingLogs.each(function() {

			var curId = $(this).children('.log-id').html();
			// console.log(curId);

			if(log.id == curId) {

				alreadyExists = true;
			}
		});

		// console.log(alreadyExists);
		if(!alreadyExists) {
			// console.log("add log");
			var html = "<div class='log'>";
			html += '<div class="log-charname f3 ">';
			html += '<p class="-nm">' + log.characterName + '</p>';
			html += '</div>';

			html += '<div class="log-date f2">';
			html += '<p class="-nm">' + log.standartDate + '</p>';
			html += '</div>';

			html += '<div class="log-content f7">';
			html += '<p class="-nm">' + log.content + '</p>';
			html += '</div>';		

			html += '<div class="log-id hidden">';
			html += log.id;
			html += '</div>';

			html += "</div>";
			if(params && params['reverse']) {
				$('.logs').append(html);
			} else {
				$('.logs').prepend(html);
			}
		}
	},

	getLastsLogs : function() {
	    $.ajax({
	       url : ajaxUrlLastsLogs,
	       type : 'GET',
	       dataType : 'json',
	       success : function(json, statut){ // success est toujours en place, bien sûr !
	           // console.log(json);
	           console.log(Logger.inited);
	           var i = 0;
	           json.forEach(function(log) {
	           		console.log(log.characterName);


	           		// setTimeout(function() {

		           		if(Logger.inited) {
		           			Logger.addLog(log);
		           		} else {
		           			Logger.addLog(log, {'reverse':true});
		           		}

	           		// }, 50*i);
	           		// i++;
	           		
	           });
	           if(!Logger.inited) {
	           		Logger.inited = true;

	           }
	       },

	       error : function(resultat, statut, erreur){

	       }

	    });

	}
	

};