<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{            // table id=''  | class='delete'
		$('table#delTable td button.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
                var id = $(this).parent().parent().attr('selectThis'); // ID
                var data = 'selectID=' + id ; // $_GET[]
                var parent = $(this).parent().parent();
                $.ajax(
                {
                    type: "POST",
                    url: "_COURSE_EDIT.php",
                    data: data,
                    cache: false,
                    success: function(output)
                    {
                        if(output!=0){
                            $('div#result').text("Record has been successfully deleted.");
                            parent.fadeOut('slow', function() {$(this).remove();});
                        }else{
                            $('div#result').text("Oops, something went wrong. Please try again later.");
                        }
                    }
                });	
            }
        });
    });
</script>