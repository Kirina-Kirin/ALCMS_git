function clickButton()
{
    $('.button').click(function(){
        var buttonValue = $(this).val();
		var arr = buttonValue.split("_");
        var ajaxurl = 'system_admin.php',
		 alert(arr[0]+":"+ arr[1]);
        data =  {arr[0]: arr[1]};
        $.post(ajaxurl, data, function (response) {
            // Response div goes here.
            alert("action performed successfully");
        });
    });

}