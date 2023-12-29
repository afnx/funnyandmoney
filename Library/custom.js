function callModal(page,title) {
	$.post("../Controllers/formPosts.php?action=callModal", {
		page : page
	}, function(data) {
		$("#generalModal .modal-title").html("");
		$("#generalModal .modal-body").html("");
		$("#generalModal .modal-title").append(title);
		$("#generalModal .modal-body").append(data);
		$(".modal").modal("hide");
		$("#generalModal").modal("show");

	});

}