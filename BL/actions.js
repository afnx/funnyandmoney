function tryParseJSON (jsonString){
		try {
			var o = JSON.parse(jsonString);

			// Handle non-exception-throwing cases:
			// Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
			// but... JSON.parse(null) returns null, and typeof null === "object", 
			// so we must check for that, too. Thankfully, null is falsey, so this suffices:
			if (o && typeof o === "object") {
				return o;
			}
		}
		catch (e) { }

		return false;
	};
	function refreshButtons(post,type){
		$.ajax({
						type: 'POST',
						url: "/BL/functions.php?function=updateButtons",
						data: {postID:post,buttonStyle:'large'},
						success: function cevapButton(e){
							if(tryParseJSON(e)==false){
								//butonlar gelsin
								if(actionConfig.pagename == 'signed'){
									$("#post"+post+" .socialButtons").html(e);
								}else if(actionConfig.pagename == 'post' || actionConfig.pagename == 'videopost'){
									$(".post-action").html(e);
								}else if(actionConfig.pagename == 'profile'){
									$("#post"+post+" .post-action").html(e);
								}
								
							}else{
								//post gitsin
								if(actionConfig.pagename == 'signed'){
									$("#post"+post).hide();
									$('.masonry').masonry('layout');
								}else if(actionConfig.pagename == 'post' || actionConfig.pagename == 'videopost'){
									$('.panel-default').hide();
								}else if(actionConfig.pagename == 'profile'){
									$("#post"+post+" .post-action").html('');
								}
							}
								
							}
		});
	};
	function action (action,platform,post,share) {
		share = typeof share !== 'undefined' ? share : null;
		$.ajax({
			type: 'POST',
			url: "/Controllers/formPosts.php?action=earnPoints",
			data: {platformID:platform,postID:post,actionID:action,shareOn:share},
			success: function cevapAction(e){
				var response = jQuery.parseJSON(e);
				if(!response.errorDetected){
					$("#newPoints").text('+'+(parseFloat(Math.floor(response.earnedPoints * 100) / 100).toFixed(2))).css("display", "inline");
					$("#newPoints").className = "label label-success";
					$("#currentPoints").fadeOut(500, function(){$("#currentPoints").text(response.newBalance)}).fadeIn(1500, function(){$("#newPoints").css("display", "none");});
					refreshButtons(post);
				}else{
					document.getElementById("modalText").innerHTML = response.errorMsg;
					document.getElementById("modalButton").click();
				}
			}
			});
	};