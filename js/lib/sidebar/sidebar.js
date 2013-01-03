// Scrolling sidebar for your website
// Downloaded from Marcofolio.net
// Read the full article: http://www.marcofolio.net/webdesign/create_a_sticky_sidebar_or_box.html

window.onscroll = function()
{
	if( window.XMLHttpRequest ) {
		if (document.documentElement.scrollTop > 221 || self.pageYOffset > 80) {
			$('#sb_content').addStyle("position",'fixed');
			$('#sb_content').addStyle("top",'0');
		} else if (document.documentElement.scrollTop < 221 || self.pageYOffset < 80) {
			$('#sb_content').addStyle("position",'absolute');
			$('#sb_content').addStyle("top",'80');
		}
	}
}