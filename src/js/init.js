$( function() {

  // 导航滑动效果
	$('a').on('click', function( event ) {
		if ( this.hash !== '' ) {
			var hash = this.hash;
			event.preventDefault();
			$('html, body').animate(
				{
					scrollTop: $( hash ).offset().top
				},
				800,
				function () {
					if ( history.pushState ) {
						history.pushState( null, null, hash );
					} else {
						location.hash = hash;
					}
				}
			);
		}
	});

  // 导航条定位效果
  $( document ).scroll( function() {
  var top = $(document).scrollTop() + 200;
  var closest = '';
  $('.cover').each( function() {
    var pageTop = $( this ).offset().top;
    if ( pageTop < top ) closest = $( this ).attr('id');
  });
  $('.navbar li').removeClass('active');
  $('.navbar li > a[href="#' + closest + '"]').parent().addClass('active');
  });

  // 选裁判时，跳过报名项目一节。同时，设定确认表单里报名类别这一项
  $( '#role-player' ).click( function () {
    enableItem();
    setType("player");
  });

  $( '#role-referee' ).click( function () {
    disableItem();
    setType("referee");
  });

  function enableItem() {
    var selects = $('.item-select');
    selects.attr('disabled', false );
    selects.css('background-color', '#fff');
    $('#js-toItem' )[0].hash = '#item';
  }

  function disableItem() {
    // 使选择项目的两个select不可用
    var selects = $('.item-select');
    selects.val(null);
    selects.attr('disabled', true );
    selects.css('background-color', '#eee');

    // 点击填写个人信息界面的下拉按钮，会跳过选择报名项目一节
    $('#js-toItem')[0].hash = '#confirm';

    // 该隐藏的都隐藏，包括选择项目一节的搭档信息填写框和确认一节的
    // 项目、搭档信息框
    //$('.partner-info').addClass('ng-hide');
    //$('.js-item-info').addClass('ng-hide');
  }

  function setType( type ) {
    $('#js-type')[0].value = type;
  }

  // 在确认表单里，强行让文本框不能输入.（如果用disabled，表单项不会提交；用readonly，不能使用html的验证）
  // 对于选择框，直接让所有option hidden =_=
  $('.confirm-form input').keydown( function( e ) {
    e.preventDefault();
  });
  $('.confirm-form input').on('keydown paste', function( e ) {
    e.preventDefault();
  });

  
  // 异步提交表单，提交前会验证
  $('.confirm-form').submit( function ( e ) {
    e.preventDefault();
    $('#modal-submit').modal( 'show' );
    // 强行让进度条跑一会儿 =_= 这么好看的进度条
    setTimeout( submitForm, 1000);
  });

  function submitForm() {
    var url = '';
    if ( $('#js-type')[0].value === "player") {
      url = 'php/newplayer.php';
    } else {
      url = 'php/newreferee.php';
    }

    try {
      $.post(url, $('.confirm-form').serialize(), function ( data, status ) {
        if ( status === 'success') {
          $('#modal-submit').modal('hide');
          $('#modal-response .modal-body').html( data );
          $('#modal-response').modal();
        } else {
          throw "服务器好像出错了= =";
        }
      });
    } catch ( err ) {
      $('#modal-submit .modal-body').html( err.message );
    }
  }
});
