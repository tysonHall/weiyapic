$(function () {
    var wrap = $('.part-3'),
        wfp = $('.part-3 #waterfall-2'),
        wfc = $('.part-3 .box'),
        wfpW = wfp[0].offsetWidth,
        winH = $('body').height(),
        wfcW = wfpW * .499,
        wfcHArr = [],
        spacing = Math.ceil((wfpW - wfcW * 2)),
        wfcLen = wfc.length,
        wfpH,
        col = [],
        total = 0,
        mt = $('.part-3 .pic-3')[0].offsetHeight,
        detail = $('.part-3 .show-detail div'),
        origin = $('.part-3 .show-origin div'),
        btnOrigin = $('.part-3 .show-detail button'),
        btnClose = $('.part-3 .show-origin button'),
        curImg = {},
        tip = $('.part-3 .tip'),
        toptip = $('.part-3 .toptip'),
        haveMore1 = true;

    $('.mainbox').css('margin-top', mt - spacing + 'px');
    toptip.html('<img src="/res/images/loading.png" style="margin:auto; width:'+(wfpW*.1)+'px; padding:'+(wfpW*.003)+'px 0;">');
    toptip.children("img").rotate({
        angle:0,
        animateTo:360
    });
    toptip.children("img").fadeOut(500,function(){
        toptip.html('<span class="get_new_btn" style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">查看最新</span>');
    });
    toptip.click(function(){
        get_newimg();
    })
    // Math.floor((winW - spacing) / (wfcW + spacing));
    total = 2;
    for (var i = 0; i < total; i++) {
        col[i] = 0;
    }
    showDetail();

    // 检测当前应所在列
    function callBack() {
        var num = 0;
        for (var i = 1; i < total; i++)
            if (col[num] > col[i]) num = i;
        return num;
    }

    // 计算位置
    function calcPos(startIndex) {
        var obj = $('.part-3 .box');
        startIndex = startIndex || 0;
        var num = callBack();
        if (obj.length == 0) return;
        obj.eq(startIndex).stop().animate({
            'left': num * (wfcW + spacing) + 'px',
            'top': col[num] + spacing + 'px'
        }, 0);
        col[num] += wfcHArr[startIndex] + spacing;
    }

    // 计算列高
    function calcH() {
        wfpH = col[0];
        for (var h = 1; h < total; h++)
            if (col[h] > wfpH) wfpH = col[h];
        wfp.css('height', wfpH + spacing);
    }
	
    function calcH1() {
        wfpH = height0[1];
		if(height1[1]>wfpH)
		{
			wfpH = height1[1];
		}
        wfp.css('height', wfpH + spacing);
    }

    // 监听动作
    var tStart = {}, tEnd = {};
    wrap.on('touchstart', function (e) {
		if(!haveMore1){
			//haveMore1 = true;
            return;
		}
        tStart = {x: e.originalEvent.touches[0].clientX, y: e.originalEvent.touches[0].clientY};
    });

    wrap.on('touchmove', function (e) {
        var movey = e.originalEvent.changedTouches[0].pageY;
        if(haveMore1 && movey - tStart.y <= -10)
        {
            tip.html('<span style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">正在加载中....</span>');
            tip.show();
        }
    });

    wrap.on('touchend', function (e) {
        tEnd = {x: e.originalEvent.changedTouches[0].clientX, y: e.originalEvent.changedTouches[0].clientY};
        if (tEnd.y - tStart.y <= -10 && haveMore1) {   // 上下拉
            if ($(window).scrollTop() >= $('.part-3.main').height() - winH * 1.2) {
                loadMore();
                tip.hide();
            }
        }
    });

    // 加载更多
    loadMore();
    function loadMore() {
        if(typeid === 0) {
            return ;
        }

        var urlArr = new Array();
        $.post('/index/myfile/ajax_file',{'typeid':typeid,'num':num[1],'count':count,'table':table},function(res){
			if(res.length == 0) {				
				haveMore1 = false;
				tip.html('<span style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">没有更多了....</span>');
				tip.show();
                if(maxid[1] == 0)
                {
                    maxid[1] = 1;
                }
				return ;
			}
            for(var i=0;i<res.length;i++)
            {
               // urlArr[i] = {};
                // urlArr.push(res[i].imageurl2);
                // urlArr[i]['imageurl1'] = res[i].imageurl1;
                // urlArr[i]['imageurl2'] = res[i].imageurl2;
                // urlArr[i]['imageurl3'] = res[i].imageurl3;
                // urlArr[i]['size'] = res[i].imagesize;
				urlArr.push({imageurl1: res[i].imageurl1, imageurl2: res[i].imageurl2, imageurl3: res[i].imageurl3, size: res[i].imagesize, height: res[i].height, width: res[i].width, id: res[i].id});
            }

            for (var i = 0; i < urlArr.length; i++) {
				var boxtop = 0;
				var boxleft = 0;
				if(height0[1] <= height1[1])
				{
					boxtop = spacing+height0[1];
					boxleft = 0;
					height0[1] += urlArr[i].height * wfcW / urlArr[i].width + spacing;
				}
				else
				{
					boxtop = spacing+height1[1];
					boxleft = wfcW + spacing;
					height1[1] += urlArr[i].height * wfcW / urlArr[i].width + spacing;
				}
                var str = '<div class="box" style="top:'+boxtop+'px; left:'+boxleft+'px;" ><img style="width:100%;" src="' + urlArr[i]['imageurl2'] + '" data-big="' + urlArr[i]['imageurl3'] + '" data-origin="' + urlArr[i]['imageurl1'] + '" data-size="'+byte_to_mbyto(urlArr[i]['size'])+'M"></div>',
                    newImg = new Image();
                newImg.src = urlArr[i].imageurl2;
                wfp.append(str);
				calcH1();
                wfcLen++;
                if(urlArr[i].id > maxid[1])
                {
                    maxid[1] = urlArr[i].id;
                }
            }  
			tip.hide();
            toptip.html('<span class="get_new_btn" style="font-size:'+(wfpW*.06)+'px; padding:'+(wfpW*.01)+'px 0;">查看最新</span>');

        },'json');

        num[1] += count;
    }

    function get_newimg()
    {
        $.post('/index/myfile/ajax_newimg', {'maxid':maxid[1],'typeid':typeid,'table':table},function(res){
            if(res > 0)
            {
                wfp.html('');
                num[1] = 0;
                height0[1] = 0;
                height1[1] = 0;
				haveMore1 = true;
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

    // 展示大图及原图
     // 展示大图及原图
    function showDetail() {
        wfc.live('click', function () {
            curImg = {
                sub: parseInt($(this).index()),
                big: $(this).find('img').attr('data-big'),
                origin: $(this).find('img').attr('data-origin'),
                size: $(this).find('img').attr('data-size')
            };
            $('body').addClass('ovf');
            detail.find('img').attr('src', curImg.big);
            origin.find('img').attr('src', curImg.origin);
            btnOrigin.find('span').text(curImg.size);
			if (!detail.find('img').hasClass('animate')) {
				transformScale($('.part-3 .show-detail img')[0], true);
			}
            else
            {
                resettransform($('.part-3 .show-detail img')[0]);
            }
        });

        detail.click(function (e) {
            // if (e.target.localName == 'div') {
                // $('body').removeClass('ovf');
            // }
			$('body').removeClass('ovf');
        });

        btnOrigin.live('click', function () {
            origin.parent().show();
            $('body').removeClass('ovf');
            transformScale($('.part-3 .show-origin img')[0], false);
        });

        btnClose.click(function () {
            origin.parent().hide();
            $('body').addClass('ovf');
        });

        $('.show-detail , .show-origin').on('touchmove', function (event) {
            event.preventDefault();
        });
    }

    function resettransform(el)
    {
        $('.imgbox img').load(function(){
            var START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
            var START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
            el.className = 'animate';

            var value = [
                'translate3d(' + START_X + 'px, ' + START_Y + 'px, 0)',
                'scale(1, 1)',
                'rotate3d(0,0,0,0deg)'
            ];

            value = value.join(" ");
            el.style.webkitTransform = value;
            el.style.mozTransform = value;
            el.style.transform = value;
        });
    }

    function transformScale(el, isMove) {
		if (el.offsetWidth == 0 || el.offsetHeight == 0) {
            setTimeout(function () {
                transformScale(el, isMove);
            }, 10);
			return;
        }
        var reqAnimationFrame = (function () {
            return window[Hammer.prefixed(window, 'requestAnimationFrame')] || function (callback) {
                    window.setTimeout(callback, 1000 / 60);
                };
        })();
        var END_X = START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
        var END_Y = START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
        var ticking = false;
        var transform;
        var mc = new Hammer.Manager(el);

        mc.add(new Hammer.Pan({threshold: 0, pointers: 0}));
        mc.add(new Hammer.Swipe()).recognizeWith(mc.get('pan'));
        mc.add(new Hammer.Rotate({threshold: 0})).recognizeWith(mc.get('pan'));
        mc.add(new Hammer.Pinch({threshold: 0})).recognizeWith([mc.get('pan'), mc.get('rotate')]);

        mc.on("panstart panmove", onPan);
        mc.on("panend", onPanend);
        mc.on("pinchstart pinchmove", onPinch);
        mc.on("swipe", onSwipe);

        mc.on("hammer.input", function (ev) {
            if (ev.isFinal) {
                // resetElement();
            }
        });

        function resetElement() {
            el.className = 'animate';
            transform = {
                translate: {x: START_X, y: START_Y},
                scale: 1,
                angle: 0,
                rx: 0,
                ry: 0,
                rz: 0
            };
            requestElementUpdate();
        }
        function resetElement_keyscale(){
            el.className = 'animate';
            transform = {
                translate: {x: END_X, y: END_Y},
                scale: transform.scale,
                angle: 0,
                rx: 0,
                ry: 0,
                rz: 0
            };
            requestElementUpdate();
        }

        function updateElementTransform() {
            var value = [
                'translate3d(' + transform.translate.x + 'px, ' + transform.translate.y + 'px, 0)',
                'scale(' + transform.scale + ', ' + transform.scale + ')',
                'rotate3d(' + transform.rx + ',' + transform.ry + ',' + transform.rz + ',' + transform.angle + 'deg)'
            ];

            value = value.join(" ");
            el.style.webkitTransform = value;
            el.style.mozTransform = value;
            el.style.transform = value;
            ticking = false;
        }

        function requestElementUpdate() {
            if (!ticking) {
                reqAnimationFrame(updateElementTransform);
                ticking = true;
            }
        }

        function onPan(ev) {
            el.className = '';
            transform.translate = {
                x: END_X + ev.deltaX,
                y: transform.scale == 1? START_Y : END_Y + ev.deltaY
            };

            requestElementUpdate();
        }

        //拖动结束
        function onPanend(ev)
        {
            END_X = transform.translate.x;
            END_Y = transform.translate.y;
        }

        var initScale = 1;
        function onPinch(ev) {
            if (ev.type == 'pinchstart') {
                initScale = transform.scale || 1;
            }

            el.className = '';
            transform.scale = (initScale * ev.scale) < 1 ? 1 : initScale * ev.scale;
            requestElementUpdate();
        }

        function onSwipe(ev) {
            if (isMove) {
                var box = $('.part-3 .box');
                if (ev.deltaX >= 10 && transform.translate.x>0) {
                    if (curImg.sub < 1) {
                        //curImg.sub = box.length - 1;
                        if(transform.scale <= 1)
                        {
                            END_X = START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
                            END_Y = START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
                            resetElement_keyscale();
                        }
						return;
                    }
                    curImg.sub--;
                    curImg.big = box.eq(curImg.sub).find('img').attr('data-big');
                    curImg.origin = box.eq(curImg.sub).find('img').attr('data-origin');
                    curImg.size = box.eq(curImg.sub).find('img').attr('data-size');
                    detail.find('img').attr('src', '');
                    origin.find('img').attr('src', '');
                    detail.find('img').attr('src', curImg.big);
                    origin.find('img').attr('src', curImg.origin);
                    btnOrigin.find('span').text(curImg.size);
                }
                if (ev.deltaX < -10 && (el.offsetWidth*transform.scale+transform.translate.x)<document.body.offsetWidth) {
                    if (curImg.sub >= box.length - 1) {
                        //curImg.sub = 0;
                        if(transform.scale <= 1)
                        {
                            END_X = START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
                            END_Y = START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
                            resetElement_keyscale();
                        }
						return;
                    }
                    curImg.sub++;
                    curImg.big = box.eq(curImg.sub).find('img').attr('data-big');
                    curImg.origin = box.eq(curImg.sub).find('img').attr('data-origin');
                    curImg.size = box.eq(curImg.sub).find('img').attr('data-size');
                    detail.find('img').attr('src', '');
                    origin.find('img').attr('src', '');
                    detail.find('img').attr('src', curImg.big);
                    origin.find('img').attr('src', curImg.origin);
                    btnOrigin.find('span').text(curImg.size);
                }
                $('.imgbox img').load(function(){
                    END_X = START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
                    END_Y = START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
                    resetElement();
                })
                $(".imgbox img").error(function(){
                    END_X = START_X = Math.floor((document.body.offsetWidth - el.offsetWidth) / 2);
                    END_Y = START_Y = Math.floor((document.body.offsetHeight - el.offsetHeight) / 2);
                    resetElement();
                });
            }
        }
        resetElement();
    }
});

