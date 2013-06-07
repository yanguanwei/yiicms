$(function() {
	$('.tabs').each(function() {
		var $block = $(this).parents('.block');
		$('li a', $(this)).click(function() {
			var href = $(this).attr('href');
			if ( href.substr(0, 1) == '#' ) {
				$(this).parents('li').siblings().removeClass('actived')
					.end().addClass('actived');
				$('.tab-content', $block).hide();
				$('#tab-' + href.substr(1), $block).show();
				return false;
			}
		});
		
		if ( $('li.actived', $(this)).length == 0 )
			$('li a', $(this)).eq(0).click();
	});
	
	//友情链接跳转
	$('#friendLinks select').change(function() {
		var $form = $('#friendLinks_Form');
		if ( $form.length == 0 ) {
			$form = $('<form target="_blank"><input type="submit" /></form>').hide();
			$('body').append($form);
		}
		$form.attr('action', $(this).val());
		$form.submit();
	});
	
	//搜索框
	var searchText = '请输入您要搜索的关键字';
	$('#searchForm').submit(function() {
		if ( $('#searchInput').val() == searchText )
			return false;
	});
	
	$('#searchInput').click(function() {
			if ( $(this).val() == searchText )
				$(this).val('');
		}).blur(function() {
			if ( !$(this).val() )
				$(this).val(searchText);
		}).blur();
});

function AddFavorite(sURL, sTitle)
{
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}

//设为首页 <a onclick="SetHome(this,window.location)">设为首页</a>
function SetHome(obj,vrl)
{
	try{ 
		obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	} catch(e) {
		if(window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			} catch (e) {
				alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage',vrl);
		}
	}
}

function setFontSize(selector, size)
{
	$(selector).css('font-size', size);
}