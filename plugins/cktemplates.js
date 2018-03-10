// Register a template definition set named "default".
CKEDITOR.addTemplates( 'default',
{


	// The name of the subfolder that contains the preview images of the templates.
	imagesPath : CKEDITOR.basePath.substr(0, CKEDITOR.basePath.indexOf("ckeditor/")) + 'plugins/template_images/',

	// Template definitions.
	templates :
		[
			{
				title: 'Image and Title',
				image: 'template1.gif',
				description: 'One main image with a title and text that surround the image.',
				html:
				'<img style="MARGIN-RIGHT: 10px" height="100" alt="" width="100" align="left"/><h3>Type the title here</h3>Type the text here'
			},
			{
				title: 'Strange Template',
				image: 'template2.gif',
				description: 'A template that defines two colums, each one with a title, and some text.',
				html:
  				'<table cellspacing="0" cellpadding="0" width="100%" border="0">'+
  				'	<tbody>'+
  				'		<tr>'+
  				'			<td width="50%">'+
  				'			<h3>Title 1</h3>'+
  				'			</td>'+
  				'			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
  				'			<td width="50%">'+
  				'			<h3>Title 2</h3>'+
  				'			</td>'+
  				'		</tr>'+
  				'		<tr>'+
  				'			<td>Text 1</td>'+
  				'			<td>&nbsp;</td>'+
  				'			<td>Text 2</td>'+
  				'		</tr>'+
  				'	</tbody>'+
  				'</table>'+
  				'More text goes here.'
			},
			{
				title: 'A title with some text and a table.',
				image: 'template3.gif',
				description: 'Text and Table',
				html:
				'<table align="left" width="80%" border="0" cellspacing="0" cellpadding="0"><tr><td>'+
				'	<h3>Title goes here</h3>'+
				'	<p>'+
				'	<table style="FLOAT: right" cellspacing="0" cellpadding="0" width="150" border="1">'+
				'		<tbody>'+
				'			<tr>'+
				'				<td align="center" colspan="3"><strong>Table title</strong></td>'+
				'			</tr>'+
				'			<tr>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'			</tr>'+
				'			<tr>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'			</tr>'+
				'			<tr>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'			</tr>'+
				'			<tr>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'				<td>&nbsp;</td>'+
				'			</tr>'+
				'		</tbody>'+
				'	</table>'+
				'	Type the text here</p>'+
				'</td></tr></table>'
			},
			{
				title: 'Simple newsletter template',
				image: 'sm_template1.gif',
				description: 'Simple newsletter template with logo, text rows and unsubscribe link.',
				html:
      '<div align="center">'+
      '  <table border="0" cellpadding="2" cellspacing="2" width="808">'+
      '    <tr>'+
      '      <td width="100%" style="border: 1px solid #99CCFF; padding: 2px">'+
      '        <img style="MARGIN-RIGHT: 10px" height="100" alt="" width="100" align="left"/ >'+
      '        <font face="Verdana, Arial, Helvetica">&lt;--set logo here<br />ENTER HEADLINE TEXT HERE</font>'+
      '        <p>&nbsp;</p>'+
      '      </td>'+
      '    </tr>'+
      '    <tr>'+
      '      <td width="100%" style="border: 1px solid #99CCFF; padding: 2px">'+
      '      <font face="Verdana, Arial, Helvetica">ENTER TEXT HERE</font>'+
      '        <p>&nbsp;</p>'+
      '      </td>'+
      '    </tr>'+
      '    <tr>'+
      '      <td width="100%" style="border: 1px solid #99CCFF; padding: 2px"><font face="Verdana, Arial, Helvetica">ENTER TEXT HERE</font>'+
      '        <p>&nbsp;</p>'+
      '      </td>'+
      '    </tr>'+
      '    <tr>'+
      '      <td style="border: 1px solid #99CCFF; padding: 2px">&nbsp;'+
      '        <p><font face="Verdana, Arial, Helvetica">IMPRESS</font></p>'+
      '        <p><font face="Verdana, Arial, Helvetica"><a href="[UnsubscribeLink]">Unsubscribe from newsletter</a></font></p>'+
      '        <p>&nbsp;</p>'+
      '      </td>'+
      '    </tr>'+
      '  </table>'+
      '</div>'
			}

		]
});