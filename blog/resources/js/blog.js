(function(window,$){
	//加载更多
	var _page_=1;
	var pathName = window.location.pathname;
	var $mainContainer = $('.tm-margin-t-big');
	var loadMoreData = function(){
		var category = $("#loadmore").attr('_category');
		_page_++;
		var loadingFlag = false,
			_url="index.php?op=getArticles&_page_="+_page_+"&category="+category,
			$loadingJuhua = $("#juhua-loading"),
			$loadMoreBtn  = $("#loadmore");
		if(!loadingFlag){
			loadingFlag = true;
			$loadingJuhua.show();
			$loadMoreBtn.hide();
			$.ajax({
				url: _url,
				type: 'GET',
				dataType: 'json', 
				timeout: 20000,
				error: function(){  },
		        success:function(result){
		        	loadingFlag = false;
		        	if(result.success == true){
		        		var articles = $(result.data);
		        		console.log(articles);
		        		if(articles.length>0){
		        			var articles_dom = '';
				    		for (var i = 0; i < articles.length; i++) {
				    			articles_dom += '<div class="col-xs-12 col-sm-12 col-md-4"><div class="article-list-box"><div class="article-list-image">';
				    			articles_dom += '<a  class="open-single-frame" href="'+articles[i].url+'">';
				    			articles_dom += '<img width="600" height="300" src="'+articles[i].icon+'" class="attachment-box-image size-box-image wp-post-image" /></a>';
				    			if (articles[i].astrict == 1){
				    				articles_dom += '<div class="astrict"><div class="text">限制</div></div>';
				    			}
				    			articles_dom += '</div><h2><a class="open-single-frame" href="'+articles[i].url+'"><span class="entry-title-primary">'+articles[i].title+'</span></a></h2>';
				    			articles_dom += '<div class="info"><div class="article-type">'+articles[i].category+'</div>';
				    			articles_dom += '<div class="article-date">'+articles[i].RelativeTime+'</div></div></div></div>';
				    		}
				    		$mainContainer.append(articles_dom);
				    	}
		        	}else{
		        		$loadMoreBtn.text('没有更多');
		        	}
	                $loadingJuhua.hide();
	                $loadMoreBtn.show();
				}
			});
		}
	}
	$(".btn-loadmore").on("click",loadMoreData);

	
}(window,jQuery));