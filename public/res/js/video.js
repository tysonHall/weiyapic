/**
 * @created 2018/1/24 14:28
 */
$(function () {
    var mt = $('.part-4 .pic-3')[0].offsetHeight,
        videoList = $('.part-4 .video-list'),
        videoLi = videoList.find('li'),
        video = videoLi.find('video'),
        btnBack = $('.btn-back'),
        wrap = $('.part-4'),
        tip = wrap.find('.tip'),
        wfpW = wrap[0].offsetWidth,
        winH = wrap[0].offsetHeight,
        toptip = $('.part-4 .toptip'),
        haveMore = true;

    $('.mainbox').css('margin-top', mt + 'px');
    toptip.html('<img src="/res/images/loading.png" style="margin:auto; width:'+(wfpW*.1)+'px; padding:'+(wfpW*.003)+'px 0;">');
    toptip.children("img").rotate({
        angle:0,
        animateTo:360
    });
    toptip.children("img").fadeOut(500,function(){
        toptip.html('<span class="get_new_btn" style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">查看最新</span>');
    });
    toptip.click(function(){
        get_newvideo();
    })

    $('.part-4 .shadow').live('click', function () {
        $(this).hide();
        btnBack.show();
        $('body').removeClass('ovf');
        $('.part-4 li.on').removeClass('on').find('video')[0].pause();
    });

    $('.part-4 li.on').live('touchmove', function (e) {
        event.preventDefault();
    });

    video.live('click',function () {
        $('body').addClass('ovf');
        $(this).parent().addClass('on');
        $(this)[0].play();
        $('.part-4 .shadow').show();
        btnBack.hide();
    });

    var tStart = {}, tEnd = {};
    wrap.on('touchstart', function (e) {
        tStart = {x: e.originalEvent.touches[0].clientX, y: e.originalEvent.touches[0].clientY};
    });

    wrap.on('touchmove', function () {
        // 是否有更多图片可加载，此处假设5秒后无更多
        if(haveMore)
        {
            tip.html('<span style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">正在加载中....</span>');
            tip.show();
        }
        
    });

    wrap.on('touchend', function (e) {
        tEnd = {x: e.originalEvent.changedTouches[0].clientX, y: e.originalEvent.changedTouches[0].clientY};
        if (tEnd.y - tStart.y <= -10 && haveMore) {   // 上下拉
            if ($(window).scrollTop() >= $('.part-4.main').height() - winH * 1.2) {
                loadMore();
                tip.hide();
            }
        }
    });

    loadMore();
    function loadMore() {
        if(typeid === 0) {
            return ;
        }
        var urlArr = new Array(); 
        $.post('/index/myfile/ajax_file',{'typeid':typeid,'num':num[2],'count':count,'table':table},function(res){
			if(res.length == 0) {				
				haveMore = false;
				tip.html('<span style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">没有更多了....</span>');
				tip.show();
                if(maxid[2] == 0)
                {
                    maxid[2] = 1;
                }
				return ;
			}
            for(var i=0;i<res.length;i++){
                urlArr[i] = {};
                urlArr[i]['videourl'] = res[i].videourl;
                urlArr[i]['imageurl'] = res[i].imageurl;
                urlArr[i]['width'] = res[i].width;
                urlArr[i]['height'] = res[i].height;
                urlArr[i]['id'] = res[i].id;
            }
            for (var i = 0; i < urlArr.length; i++) {
                var str = '<li> <div class="shadow"></div> <video height="'+Math.floor($(window).width() * (urlArr[i].height/urlArr[i].width))+'" src="' + urlArr[i]['videourl'] + '" controls x5-playsinline="true" playsinline="true" webkit-playsinline="true" poster="'+urlArr[i]['imageurl']+'"></video> </li>';
                videoList.find('ul').append(str);
                if(urlArr[i].id > maxid[2])
                {
                    maxid[2] = urlArr[i].id;
                }
            }
			tip.hide();
            toptip.html('<span class="get_new_btn" style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">查看最新</span>');
        },'json'); 
         num[2] += count;
    }

    function get_newvideo()
    {
        $.post('/index/myfile/ajax_newimg', {'maxid':maxid[2],'typeid':typeid,'table':table},function(res){
            if(res > 0)
            {
                videoList.find('ul').html('');
                num[2] = 0;
                height0[2] = 0;
                height1[2] = 0;
                haveMore0 = true;
                loadMore();
            }
        });
        toptip.html('<img src="/res/images/loading.png" style="margin:auto; width:'+(wfpW*.1)+'px; padding:'+(wfpW*.003)+'px 0;">');
        toptip.children("img").rotate({
            angle:0,
            animateTo:360
        });
        toptip.children("img").fadeOut(500,function(){
            toptip.html('<span class="get_new_btn" style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">查看最新</span>');
        })
    }
});
