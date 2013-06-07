var CKFinderInput = function(basePath, startupPath, id )
{
	var $input = $('#' + id );
	var $preview = $('#' + id + '_preview');
	
	function showThumb()
	{
		var fileUrl = $input.val();
		
		if (fileUrl) {
			$preview.html('<div class="thumb"><a href="' + fileUrl + '" target="_blank"><img src="' + fileUrl + '" /></a></div>');
			$preview.show();
		}
	}
	
	function setFileField (fileUrl, data) {
		$input.val(fileUrl);
		showThumb();
		// It is not required to return any value.
		// When false is returned, CKFinder will not close automatically.
		//return false;
	}

	$('#select_' + id).click(function() {
		var finder;
		finder = new CKFinder();
		finder.basePath = basePath;
		//Startup path in a form: "Type:/path/to/directory/"
		finder.startupPath = startupPath;
		finder.selectActionFunction = setFileField;
		
		finder.popup();		  
	});
	
	showThumb();
}