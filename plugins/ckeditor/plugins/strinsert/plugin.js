/**
 * @license Copyright © 2013 Stuart Sillitoe <stuart@vericode.co.uk>
 * This work is mine, and yours. You can modify it as you wish.
 *
 * Stuart Sillitoe
 * stuartsillitoe.co.uk
 *
 */

CKEDITOR.plugins.add('strinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		//  array of strings to choose from that'll be inserted into the editor
		var strings = [];
		strings.push(['$NAME', 'Student\'s Name', 'Student\'s Name']);
		strings.push(['$FATHER', 'Father\'s Name', 'Father\'s Name']);
		strings.push(['$MOTHER', 'Mother\'s Name', 'Mother\'s Name']);
		strings.push(['$VILLAGE', 'Village', 'Village']);
		strings.push(['$POST', 'Post', 'Post']);
		strings.push(['$THANA', 'Thana', 'Thana']);
		strings.push(['$DISTRICT', 'District', 'District']);
		strings.push(['$STUDENT_ID', 'Student ID', 'Student ID']);
		strings.push(['$ROLL', 'Roll', 'Roll']);
		strings.push(['$SESSION', 'Session', 'Session']);
		strings.push(['$CLASS', 'Class', 'Class']);
		strings.push(['$DEPT', 'Group / Dept.', 'Group / Dept.']);
		strings.push(['$SECTION', 'Section', 'Section']);
		strings.push(['$SHIFT', 'Shift', 'Shift']);
		strings.push(['$STUDY_YEAR', 'Study Year', 'Study Year']);
		strings.push(['$EXAM_YEAR', 'Exam Year', 'Exam Year']);
		strings.push(['$RESULT', 'Result', 'Result']);
		strings.push(['$TO_DATE', 'Date', 'Date']);

		// add the menu to the editor
		editor.ui.addRichCombo('strinsert',
		{
			label: 		'Variable',
			title: 		'Variable',
			voiceLabel: 'Variable',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Variable" );
				for (var i in strings)
				{
					this.add(strings[i][0], strings[i][1], strings[i][2]);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});