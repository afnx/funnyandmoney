<script>
$.ajax({
			type: 'POST',
			url: "Controllers/formPosts.php?action=logout",
			success: function cevap(e){

				document.location.href="/";

			}
		})
</script>
