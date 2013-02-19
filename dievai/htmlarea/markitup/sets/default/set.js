// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	previewParserPath : 'htmlarea/markitup/utils/manager/scripts/preview.php',
	fileManagerPath : 'utils/manager/index.php',
    onShiftEnter:      {keepDefault:false, replaceWith:'<br />\n'},
    onCtrlEnter:      {keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
    onTab:            {keepDefault:false, replaceWith:'    '},
    markupSet:  [
        {name:'Heading 1', key:'1', openWith:'<h1(!( class="[![Class]!]")!)>', closeWith:'</h1>', placeHolder:'Your title here...' },
        {name:'Heading 2', key:'2', openWith:'<h2(!( class="[![Class]!]")!)>', closeWith:'</h2>', placeHolder:'Your title here...' },
        {name:'Heading 3', key:'3', openWith:'<h3(!( class="[![Class]!]")!)>', closeWith:'</h3>', placeHolder:'Your title here...' },
        {name:'Heading 4', key:'4', openWith:'<h4(!( class="[![Class]!]")!)>', closeWith:'</h4>', placeHolder:'Your title here...' },
        {name:'Heading 5', key:'5', openWith:'<h5(!( class="[![Class]!]")!)>', closeWith:'</h5>', placeHolder:'Your title here...' },
        {name:'Heading 6', key:'6', openWith:'<h6(!( class="[![Class]!]")!)>', closeWith:'</h6>', placeHolder:'Your title here...' },
        {name:'Paragraph', openWith:'<p(!( class="[![Class]!]")!)>', closeWith:'</p>'  },
        {separator:'---------------' },
        {name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
        {name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
        {name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
        {separator:'---------------' },
        {name:'Ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
        {name:'Ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
        {name:'Li', openWith:'<li>', closeWith:'</li>' },
        {separator:'---------------' },
        {name:'Picture', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
        {name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
        {separator:'---------------' },
        {name:'Clean', className:'clean', replaceWith:function(markitup) {
            return markitup.selection.replace(/<(.*?)>/g, "")
        } },
        {name:'Preview', className:'preview',  call:'preview'},
        {separator:'---------------' },
        {name:'RSS Feed Grabber', className:'rssFeedGrabber', replaceWith:function(markItUp) {
            return miu.rssFeedGrabber(markItUp)
        } },
        {    name:'Table generator',
            className:'tablegenerator',
            placeholder:"Your text here...",
            replaceWith:function(markItUp) {
                cols = prompt("How many cols?");
                rows = prompt("How many rows?");
                html = "<table>\n";
                if (markItUp.altKey) {
                    html += " <tr>\n";
                    for (c = 0; c < cols; c++) {
                        html += "! [![TH" + (c + 1) + " text:]!]\n";
                    }
                    html += " </tr>\n";
                }
                for (r = 0; r < rows; r++) {
                    html += " <tr>\n";
                    for (c = 0; c < cols; c++) {
                        html += "  <td>" + (markItUp.placeholder || "") + "</td>\n";
                    }
                    html += " </tr>\n";
                }
                html += "</table>\n";
                return html;
            }
        },
        {name:'gadget', className:'gadget', openWith:'<script src="http://www.gmodules.com/ig/ifr?url=[![Link:!:http://]!]&amp;synd=open&amp;w=[![Width:!:200]!]&amp;h=[![Height:!:200]!]&amp;title=&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js">', closeWith:'</script>'},

        {name:'Size',className:'fonts', key:'S', openWith:'<font size="[![Text size]!]]">', closeWith:'</font>',
            dropMenu :[
                {name:'Big', openWith:'<font size="10">', closeWith:'</font>' },
                {name:'Normal', openWith:'<font size="5">', closeWith:'</font>' },
                {name:'Small', openWith:'<font size="1">', closeWith:'</font>' }
            ]},
        {    name:'Colors', className:'palette', dropMenu: [
            {name:'Yellow', openWith:'<font color="#FCE94F">', closeWith:'</font>',    className:"col1-1" },
            {name:'Yellow', openWith:'<font color="#EDD400">', closeWith:'</font>',    className:"col1-2" },
            {name:'Yellow', openWith:'<font color="#C4A000">', closeWith:'</font>',    className:"col1-3" },

            {name:'Orange', openWith:'<font color="#FCAF3E">', closeWith:'</font>',    className:"col2-1" },
            {name:'Orange', openWith:'<font color="#F57900">', closeWith:'</font>', className:"col2-2" },
            {name:'Orange', openWith:'<font color="#CE5C00">', closeWith:'</font>',    className:"col2-3" },

            {name:'Brown', openWith:'<font color="#E9B96E">', closeWith:'</font>', className:"col3-1" },
            {name:'Brown', openWith:'<font color="#C17D11">', closeWith:'</font>',    className:"col3-2" },
            {name:'Brown', openWith:'<font color="#8F5902">', closeWith:'</font>',    className:"col3-3" },

            {name:'Green', openWith:'<font color="#8AE234">', closeWith:'</font>',     className:"col4-1" },
            {name:'Green', openWith:'<font color="#73D216">', closeWith:'</font>',    className:"col4-2" },
            {name:'Green', openWith:'<font color="#4E9A06">', closeWith:'</font>',    className:"col4-3" },

            {name:'Blue', openWith:'<font color="#729FCF">', closeWith:'</font>',     className:"col5-1" },
            {name:'Blue', openWith:'<font color="#3465A4">', closeWith:'</font>',    className:"col5-2" },
            {name:'Blue', openWith:'<font color="#204A87">', closeWith:'</font>',    className:"col5-3" },

            {name:'Purple', openWith:'<font color="#AD7FA8">', closeWith:'</font>',    className:"col6-1" },
            {name:'Purple', openWith:'<font color="#75507B">', closeWith:'</font>',    className:"col6-2" },
            {name:'Purple', openWith:'<font color="#5C3566">', closeWith:'</font>',    className:"col6-3" },

            {name:'Red', openWith:'<font color="#EF2929">', closeWith:'</font>',    className:"col7-1" },
            {name:'Red', openWith:'<font color="#CC0000">', closeWith:'</font>',    className:"col7-2" },
            {name:'Red', openWith:'<font color="#A40000">', closeWith:'</font>',    className:"col7-3" },

            {name:'Gray', openWith:'<font color="#FFFFFF">', closeWith:'</font>',    className:"col8-1" },
            {name:'Gray', openWith:'<font color="#D3D7CF">', closeWith:'</font>', className:"col8-2" },
            {name:'Gray', openWith:'<font color="#BABDB6">', closeWith:'</font>',    className:"col8-3" },

            {name:'Gray', openWith:'<font color="#888A85">', closeWith:'</font>',    className:"col9-1" },
            {name:'Gray', openWith:'<font color="#555753">', closeWith:'</font>',    className:"col9-2" },
            {name:'Gray', openWith:'<font color="#000000">', closeWith:'</font>',    className:"col9-3" }
        ]
        },
        {
            name:'Uploaded picture', key:'m', replaceWith:function(markItUp) {
                //window.open(markItUp.root + "utils/manager/index.php?id=" + markItUp.textarea.id,"mywindow","menubar=1,resizable=1,width=820,height=500");
                window.open(markItUp.root + mySettings.fileManagerPath +"?id=" + markItUp.textarea.id,"mywindow","menubar=1,resizable=1,width=850,height=500");
            //D:\Darbai\web\UniServer\www\mm\dievai\htmlarea\markitup\utils\manager
            }
        },
		{
			name:'New Page', key: 'e', replaceWith:'\n===page===\n'
		}
    ]
}
//RSS grabber


// mIu nameSpace to avoid conflict.
miu = {
    rssFeedGrabber: function(markItUp) {
        var feed, limit = 100;
        url = prompt('Rss Feed Url', 'http://rss.news.yahoo.com/rss/topstories');
        if (markItUp.altKey) {
            limit = prompt('Top stories', '5');
        }
        $.ajax({
            async:     false,
            type:     "POST",
            url:     markItUp.root + "sets/default/utils/rssfeed/grab.php",
            data:    "url=" + url + "&limit=" + limit,
            success:function(content) {
                feed = content;
            }
        }
                );
        if (feed == "MIU:ERROR") {
            alert("Can't find a valid RSS Feed at " + url);
            return false;
        }
        return feed;
    }
}

