jQuery(document).ready(function ($) {

	// Initialize the CodeMirror editor
	if ($('#content').length > 0) {
		var content_mode = $("#content").attr('mode');
		if (content_mode == 'html') {
			var content_mode = {
				name: "htmlmixed",
				scriptTypes: [{
					matches: /\/x-handlebars-template|\/x-mustache/i,
					mode: null
				}]
			};
		}
		var options = {
			lineNumbers: true,
			mode: content_mode,
			matchBrackets: true
		};
		if (typeof CCJ !== 'undefined' && CCJ.scroll !== '0') {
			options['scrollbarStyle'] = "simple";
		}


		var cm_width = $('#title').width() + 16;
		var cm_height = 500;

		var editor = CodeMirror.fromTextArea(document.getElementById("content"), options);

		editor.setSize(cm_width, cm_height);

		$('.CodeMirror').resizable({
			resize: function () {
				editor.setSize($(this).width(), $(this).height());
			},
			maxWidth: cm_width,
			minWidth: cm_width,
			minHeight: 200

		});

		$(window).resize(function () {
			var cm_width = $('#title').width() + 16;
			var cm_height = $('.CodeMirror').height();
			editor.setSize(cm_width, cm_height);
		});


	}
});

